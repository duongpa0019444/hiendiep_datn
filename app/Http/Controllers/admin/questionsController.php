<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\answers;
use App\Models\questions;
use App\Models\Quizzes;
use App\Models\sentenceAnswers;
use App\Models\sentenceQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class questionsController extends Controller
{
    //
    public function store(Request $request)
    { // 1. VALIDATE
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'content' => 'required|string',
            'type' => 'required|in:single,multiple',
            'points' => 'required|min:0.1',

            'answers' => 'required|array|min:2',
            'answers.*.content' => 'required|string',
            'answers.*.is_correct' => 'required|in:0,1',
        ], [
            'quiz_id.required' => 'Vui lòng chọn bài quiz.',
            'quiz_id.integer' => 'ID của quiz phải là số.',
            'quiz_id.exists' => 'Quiz đã chọn không tồn tại trong hệ thống.',

            'content.required' => 'Vui lòng nhập nội dung câu hỏi.',
            'content.string' => 'Nội dung câu hỏi phải là chuỗi ký tự.',

            'type.required' => 'Vui lòng chọn loại câu hỏi.',
            'type.in' => 'Loại câu hỏi không hợp lệ. Chỉ chấp nhận: single hoặc multiple.',

            'points.required' => 'Vui lòng nhập điểm cho câu hỏi.',
            'points.min' => 'Điểm tối thiểu phải là 0.1.',

            'answers.required' => 'Vui lòng nhập ít nhất 2 đáp án.',
            'answers.array' => 'Đáp án phải được gửi dưới dạng mảng.',
            'answers.min' => 'Phải có ít nhất 2 đáp án.',

            'answers.*.content.required' => 'Một hoặc nhiều đáp án chưa được nhập nội dung.',
            'answers.*.content.string' => 'Nội dung đáp án phải là chuỗi ký tự.',
            'answers.*.is_correct.required' => 'Vui lòng chọn đúng/sai cho từng đáp án.',
            'answers.*.is_correct.in' => 'Giá trị đúng/sai phải là 0 hoặc 1.',
        ]);

        // 2. GỘP LỖI (nếu có)
        if ($validator->fails()) {
            $rawErrors = $validator->errors()->toArray();
            $filteredErrors = $rawErrors;

            // Gộp lỗi nội dung
            $contentErrors = array_filter(array_keys($rawErrors), function ($key) {
                return preg_match('/^answers\.\d+\.content$/', $key);
            });

            if (!empty($contentErrors)) {
                $filteredErrors['answers'] = ['Vui lòng nhập nội dung cho tất cả các đáp án.'];
                foreach ($contentErrors as $key) {
                    unset($filteredErrors[$key]);
                }
            }

            // Gộp lỗi is_correct
            $correctErrors = array_filter(array_keys($rawErrors), function ($key) {
                return preg_match('/^answers\.\d+\.is_correct$/', $key);
            });

            if (!empty($correctErrors)) {
                $filteredErrors['answers'][] = 'Vui lòng chọn ít nhất một đáp án đúng..';
                foreach ($correctErrors as $key) {
                    unset($filteredErrors[$key]);
                }
            }

            return response()->json(['errors' => $filteredErrors], 422);
        }

        // 3. KIỂM TRA CÓ ÍT NHẤT 1 ĐÁP ÁN ĐÚNG
        $hasCorrectAnswer = collect($request->input('answers'))->contains(function ($ans) {
            return isset($ans['is_correct']) && (string)$ans['is_correct'] === '1';
        });

        if (!$hasCorrectAnswer) {
            return response()->json([
                'errors' => [
                    'answers' => ['Vui lòng chọn ít nhất một đáp án đúng.']
                ]
            ], 422);
        }


        // Xử lý lưu trữ câu hỏi và đáp án
        $question = questions::create([
            'quiz_id' => $request->quiz_id,
            'content' => $request->content,
            'type' => $request->type,
            'points' => $request->points,
            'explanation' => $request->explanation ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Lưu đáp án
        $answersToInsert = [];

        foreach ($request->answers as $answer) {
            $answersToInsert[] = [
                'question_id' => $question->id,
                'content' => $answer['content'],
                'is_correct' => $answer['is_correct'] == '1' ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        answers::insert($answersToInsert);
        $this->logAction(
            'update',
            Quizzes::class,
            $question->id,
            Auth::user()->name . ' đã tạo câu hỏi trắc nghiệm: ' . $question->content
        );
        return response()->json([
            'message' => 'Câu hỏi và đáp án đã được lưu thành công.',
            'question' => $question,
            'answers' => $answersToInsert,
            'question_type' => 'multiple_choice'
        ], 201);

    }



    public function edit($id){
        $question = questions::findOrFail($id);

        if (!$question) {
            return response()->json(['error' => 'Câu hỏi không tồn tại.'], 404);
        }

        // Lấy tất cả các đáp án liên quan đến câu hỏi này
        $answers = answers::where('question_id', $id)->get();

        return response()->json([
            'question' => $question,
            'answers' => $answers
        ], 200);
    }

    public function update($id, Request $request)
    {
         $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'content' => 'required|string',
            'type' => 'required|in:single,multiple',
            'points' => 'required|min:0.1',

            'answers' => 'required|array|min:2',
            'answers.*.content' => 'required|string',
            'answers.*.is_correct' => 'required|in:0,1',
        ], [
            'quiz_id.required' => 'Vui lòng chọn bài quiz.',
            'quiz_id.integer' => 'ID của quiz phải là số.',
            'quiz_id.exists' => 'Quiz đã chọn không tồn tại trong hệ thống.',

            'content.required' => 'Vui lòng nhập nội dung câu hỏi.',
            'content.string' => 'Nội dung câu hỏi phải là chuỗi ký tự.',

            'type.required' => 'Vui lòng chọn loại câu hỏi.',
            'type.in' => 'Loại câu hỏi không hợp lệ. Chỉ chấp nhận: single hoặc multiple.',

            'points.required' => 'Vui lòng nhập điểm cho câu hỏi.',
            'points.min' => 'Điểm tối thiểu phải là 0.1.',

            'answers.required' => 'Vui lòng nhập ít nhất 2 đáp án.',
            'answers.array' => 'Đáp án phải được gửi dưới dạng mảng.',
            'answers.min' => 'Phải có ít nhất 2 đáp án.',

            'answers.*.content.required' => 'Một hoặc nhiều đáp án chưa được nhập nội dung.',
            'answers.*.content.string' => 'Nội dung đáp án phải là chuỗi ký tự.',
            'answers.*.is_correct.required' => 'Vui lòng chọn đúng/sai cho từng đáp án.',
            'answers.*.is_correct.in' => 'Giá trị đúng/sai phải là 0 hoặc 1.',
        ]);

        // 2. GỘP LỖI (nếu có)
        if ($validator->fails()) {
            $rawErrors = $validator->errors()->toArray();
            $filteredErrors = $rawErrors;

            // Gộp lỗi nội dung
            $contentErrors = array_filter(array_keys($rawErrors), function ($key) {
                return preg_match('/^answers\.\d+\.content$/', $key);
            });

            if (!empty($contentErrors)) {
                $filteredErrors['answers'] = ['Vui lòng nhập nội dung cho tất cả các đáp án.'];
                foreach ($contentErrors as $key) {
                    unset($filteredErrors[$key]);
                }
            }

            // Gộp lỗi is_correct
            $correctErrors = array_filter(array_keys($rawErrors), function ($key) {
                return preg_match('/^answers\.\d+\.is_correct$/', $key);
            });

            if (!empty($correctErrors)) {
                $filteredErrors['answers'][] = 'Vui lòng chọn ít nhất một đáp án đúng..';
                foreach ($correctErrors as $key) {
                    unset($filteredErrors[$key]);
                }
            }

            return response()->json(['errors' => $filteredErrors], 422);
        }

        // 3. KIỂM TRA CÓ ÍT NHẤT 1 ĐÁP ÁN ĐÚNG
        $hasCorrectAnswer = collect($request->input('answers'))->contains(function ($ans) {
            return isset($ans['is_correct']) && (string)$ans['is_correct'] === '1';
        });

        if (!$hasCorrectAnswer) {
            return response()->json([
                'errors' => [
                    'answers' => ['Vui lòng chọn ít nhất một đáp án đúng.']
                ]
            ], 422);
        }

        $question = questions::findOrFail($id);

        // Cập nhật câu hỏi
        $question->content = $request->input('content');
        $question->type = $request->input('type');
        $question->points = $request->input('points');
        $question->explanation = $request->input('explanation', null);
        $question->updated_at = now();
        $question->save();
        // Xóa tất cả các đáp án cũ
        answers::where('question_id', $id)->delete();
        // Lưu các đáp án mới
        $answersToInsert = [];
        foreach ($request->input('answers') as $answer) {
            $answersToInsert[] = [
                'question_id' => $question->id,
                'content' => $answer['content'],
                'is_correct' => $answer['is_correct'] == '1' ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        answers::insert($answersToInsert);

        $this->logAction(
            'update',
            Quizzes::class,
            $question->id,
            Auth::user()->name . ' đã cập nhật câu hỏi trắc nghiệm: ' . $question->content
        );

        return response()->json([
            'message' => 'Câu hỏi và đáp án đã được cập nhật thành công.',
            'question' => $question,
            'answers' => $answersToInsert,
            'question_type' => 'multiple_choice'
        ], 200);
    }

    public function delete($id)
    {
        $question = questions::findOrFail($id);

        if (!$question) {
            return response()->json(['error' => 'Câu hỏi không tồn tại.'], 404);
        }

        // Xóa tất cả các đáp án liên quan đến câu hỏi này
        answers::where('question_id', $id)->delete();
        $this->logAction(
            'delete',
            Quizzes::class,
            $question->id,
            Auth::user()->name . ' đã xóa câu hỏi trắc nghiệm: ' . $question->content
        );
        // Xóa câu hỏi
        $question->delete();

        return response()->json(['message' => 'Câu hỏi và đáp án đã được xóa thành công.'], 200);
    }











    // Quesntion Sentence ----------------------------------------------------------
    public function storeSentence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'type' => 'required|in:fill,reorder',
            'prompt' => 'required|string',
            'correct_answer' => 'required|string',
            'points' => 'required|min:0.1',
            'explanation' => 'nullable|string',
        ], [
            'quiz_id.required' => 'Vui lòng chọn bài quiz.',
            'quiz_id.integer' => 'ID quiz không hợp lệ.',
            'quiz_id.exists' => 'Quiz không tồn tại.',

            'type.required' => 'Vui lòng chọn loại câu hỏi.',
            'type.in' => 'Loại câu hỏi không hợp lệ.',

            'prompt.required' => 'Vui lòng nhập nội dung câu hỏi.',
            'correct_answer.required' => 'Vui lòng nhập đáp án đúng.',
            'points.required' => 'Vui lòng nhập số điểm.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Tạo câu hỏi
        $question = sentenceQuestions::create([
            'quiz_id' => $request->quiz_id,
            'type' => $request->type,
            'prompt' => $request->prompt,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
            'explanation' => $request->explanation ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->logAction(
            'update',
            Quizzes::class,
            $question->id,
            Auth::user()->name . ' đã tạo câu hỏi ' .
            ($question->type == 'fill'
                ? 'điền từ: '
                : 'sắp xếp câu: ')
            . $question->prompt
        );

        return response()->json([
            'message' => 'Thêm câu hỏi thành công.',
            'question' => $question,
            'question_type' => 'fill_blank'

        ], 200);
    }



    public function editSentence($id)
    {
        $questionSentence = sentenceQuestions::findOrFail($id);

        if (!$questionSentence) {
            return response()->json(['error' => 'Câu hỏi không tồn tại.'], 404);
        }

        return response()->json([
            'question' => $questionSentence
        ], 200);
    }


    public function updateSentence($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'type' => 'required|in:fill,reorder',
            'prompt' => 'required|string',
            'correct_answer' => 'required|string',
            'points' => 'required|min:0.1',
            'explanation' => 'nullable|string',
        ], [
            'quiz_id.required' => 'Vui lòng chọn bài quiz.',
            'quiz_id.integer' => 'ID quiz không hợp lệ.',
            'quiz_id.exists' => 'Quiz không tồn tại.',

            'type.required' => 'Vui lòng chọn loại câu hỏi.',
            'type.in' => 'Loại câu hỏi không hợp lệ.',

            'prompt.required' => 'Vui lòng nhập nội dung câu hỏi.',
            'correct_answer.required' => 'Vui lòng nhập đáp án đúng.',
            'points.required' => 'Vui lòng nhập số điểm.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $questionSentence = sentenceQuestions::findOrFail($id);

        // Cập nhật câu hỏi
        $questionSentence->quiz_id = $request->quiz_id;
        $questionSentence->type = $request->type;
        $questionSentence->prompt = $request->prompt;
        $questionSentence->correct_answer = $request->correct_answer;
        $questionSentence->points = $request->points;
        $questionSentence->explanation = $request->explanation ?? null;
        $questionSentence->updated_at = now();
        $questionSentence->save();
        $this->logAction(
            'update',
            Quizzes::class,
            $questionSentence->id,
            Auth::user()->name . ' đã cập nhật câu hỏi ' .
            ($questionSentence->type == 'fill'
                ? 'điền từ: '
                : 'sắp xếp câu: ')
            . $questionSentence->prompt
        );

        return response()->json([
            'message' => 'Câu hỏi đã được cập nhật thành công.',
            'question' => $questionSentence,
            'question_type' => 'fill_blank'
        ], 200);
    }



    public function deleteSentence($id)
    {
        $questionSentence = sentenceQuestions::findOrFail($id);

        if (!$questionSentence) {
            return response()->json(['error' => 'Câu hỏi không tồn tại.'], 404);
        }

        // Xóa tất cả các đáp án liên quan đến câu hỏi này
        sentenceAnswers::where('question_id', $id)->delete();
        $this->logAction(
            'update',
            Quizzes::class,
            $questionSentence->id,
            Auth::user()->name . ' đã xóa câu hỏi: ' . $questionSentence->prompt
        );
        // Xóa câu hỏi
        $questionSentence->delete();

        return response()->json(['message' => 'Câu hỏi và đáp án đã được xóa thành công.'], 200);
    }

}
