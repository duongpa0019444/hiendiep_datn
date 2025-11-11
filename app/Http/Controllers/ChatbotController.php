<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        // Log::error("User message: " . $userMessage);

        $history = $request->input('history', []);
        $file = $request->input('file'); // {data, mime_type}
        $apiKey = env('GEMINI_API_KEY');

        // Format history
        $formattedHistory = [];
        foreach ($history as $item) {
            $formattedHistory[] = [
                "role" => $item['role'],
                "parts" => [["text" => $item['text']]]
            ];
        }

        // Log::debug($formattedHistory);
        // Parts cho user message
        $parts = [];
        if ($userMessage) {
            $parts[] = ["text" => $userMessage];
        }
        if ($file && isset($file['data'], $file['mime_type'])) {
            $parts[] = [
                "inline_data" => [
                    "mime_type" => $file['mime_type'],
                    "data" => $file['data']
                ]
            ];
        }

        // Bước 1: lấy context từ DB
        $context = $this->retrieveContext($userMessage);
        // Log::error("Context từ DB: " . $context);

        $keyword = null;

        // Bước 2: nếu chưa có context, nhờ Gemini phân tích keyword
        if (empty($context) && !empty($userMessage)) {
            $intentPayload = [
                "contents" => [
                    [
                        "role" => "user",
                        "parts" => [[
                            "text" => "Phân tích câu hỏi sau và trả về keyword chính để tìm dữ liệu: \"$userMessage\". Chỉ trả về keyword ngắn gọn."
                        ]]
                    ]
                ]
            ];

            try {
                $intentResponse = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}",
                    $intentPayload
                );

                $intentData = $intentResponse->json();
                $keyword = $intentData['candidates'][0]['content']['parts'][0]['text'] ?? "";

                // Log::error("Keyword phân tích: " . $keyword);

                if (!empty($keyword)) {
                    $context = $this->retrieveContext($keyword);
                }
            } catch (\Exception $e) {
                Log::error("Lỗi khi phân tích keyword: " . $e->getMessage());
            }
        }

        // Ghép system instruction
        $systemInstruction = [
            "role" => "model",
            "parts" => [[
                "text" => <<<'PROMPT'
                Bạn là một trợ lý AI thân thiện, trả lời bằng tiếng Việt tự nhiên, ngắn gọn và dễ hiểu.
                Không cần chào đầu câu (ví dụ: "Chào bạn", "Xin chào"), chỉ trả lời trực tiếp vào nội dung.
                Nguyên tắc trả lời (rõ ràng — bắt buộc):

                1) Khi **có dữ liệu nội bộ (context)**:
                - HÃY DÙNG dữ liệu đó để trả lời chính xác và cô đọng.
                - KHÔNG bao giờ nói những cụm từ dạng: "dựa trên dữ liệu đã cho", "dựa vào dữ liệu bạn cung cấp", "theo tài liệu", "không được đề cập trong tài liệu hiện có" — tức là **không** giải thích nguồn theo kiểu máy móc.
                - Thay vào đó, **trình bày trực tiếp**: ví dụ: "Hiện tại Hanwha đang tuyển: Backend, Frontend, Tester..." hoặc "Nhân viên hưởng 12 ngày nghỉ phép/năm; tăng theo thâm niên. Bạn cần chi tiết phần nào?"

                2) Khi **có nhiều đoạn context**: chọn thông tin **liên quan nhất**, tổng hợp ngắn gọn (1–3 câu). Nếu cần, gợi ý bước tiếp: "Bạn muốn mình gửi mô tả chi tiết vị trí nào?" — KHÔNG liệt kê toàn bộ nội dung dài dòng.

                3) Khi **KHÔNG có dữ liệu phù hợp**:
                - KHÔNG bịa thông tin.
                - Trả lời lịch sự, ngắn gọn và hướng dẫn người dùng làm tiếp: ví dụ
                    "Mình chưa tìm thấy thông tin cụ thể về câu hỏi này. Bạn có thể hỏi lại theo cách khác, hoặc mình gửi link trang tuyển dụng/đăng ký liên hệ HR giúp?"
                - Không dùng câu máy móc như "Không được đề cập trong tài liệu hiện có."

                4) Ngôn ngữ & phong cách:
                - Giữ giọng thân thiện, ngắn gọn, tối đa 2–4 câu cho câu trả lời sơ bộ.
                - Nếu người hỏi cần chi tiết, hỏi lại một câu phụ để làm rõ (ví dụ: "Bạn muốn biết về lương hay yêu cầu công việc?").

                5) Cấm & ví dụ:
                - **Cấm**: "Dựa trên dữ liệu đã cho..." / "Theo tài liệu" / "không được đề cập..." (không bao giờ dùng).
                - **Ví dụ tốt**: "Hiện tại công ty đang tuyển 3 vị trí: A, B, C. Bạn cần mình gửi mô tả vị trí nào?"
                - **Ví dụ xấu**: "Dựa trên dữ liệu bạn cung cấp, không được đề cập..." (không dùng).

                Mục tiêu: trả lời chính xác, tự nhiên, không lộ quá trình truy xuất dữ liệu, và không bịa khi thiếu thông tin.
            PROMPT
            ]]
        ];

        // Bước 3: Nếu có context → gọi Gemini lần cuối
        if (!empty($context)) {
            $parts[] = ["text" => "Dữ liệu nội bộ tham khảo:\n" . $context];

            $payload = [
                "contents" => array_merge([$systemInstruction], $formattedHistory, [
                    ["role" => "user", "parts" => $parts]
                ])
            ];
            Log::debug($payload);
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                    $payload
                );

                $data = $response->json();
                // Log::debug("Phản hồi từ Gemini: " . json_encode($data));
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ??
                    "Xin lỗi, mình chưa tìm thấy câu trả lời phù hợp.";

                return response()->json([
                    "reply" => $reply,
                    "context" => $context,
                    "keyword" => $keyword,
                    "raw" => $data,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "reply" => "Đã xảy ra lỗi khi gọi AI: " . $e->getMessage(),
                ], 500);
            }
        }

        // Bước 4: Nếu context vẫn rỗng → trả về thông báo thân thiện
        return response()->json([
            "reply" => "Xin lỗi, mình chưa có dữ liệu liên quan.",
            "context" => "",
            "keyword" => $keyword,
            "raw" => null
        ]);
    }



    //Hàm RAG: tìm dữ liệu liên quan từ DB để làm ngữ cảnh cho AI
    private function retrieveContext($message)
    {
        if (!$message) return "";

        // Tìm trong bảng bot_training_data
        $faqs = DB::table('bot_training_data')
            ->whereRaw("MATCH(question, keywords) AGAINST(? IN NATURAL LANGUAGE MODE)", [$message])
            ->limit(3)
            ->get();

        $docs = DB::table('bot_documents')
            ->whereRaw("MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)", [$message])
            ->limit(2)
            ->get();

        $context = "";

        foreach ($faqs as $faq) {
            $context .= "Q: {$faq->question}\nA: {$faq->answer}\n\n";
        }

        foreach ($docs as $doc) {
            $context .= "Doc: {$doc->title}\nNội dung: " . substr($doc->content, 0, 300) . "...\n\n";
        }

        return $context;
    }
}
