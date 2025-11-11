<?php

namespace App\Http\Controllers\Admin\Bot;

use App\Http\Controllers\Controller;
use App\Models\TrainingDocument;
use Illuminate\Http\Request;
use App\Services\DocumentReaderService;

class BotDocumentController extends Controller
{
    public function filter(Request $request, $id)
    {
        $query = TrainingDocument::where('training_set_id', $id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where('title', 'like', '%' . $keyword . '%');
        }


        $datas = $query->paginate($request->limit ?? 10);

        // Giữ lại tham số lọc khi phân trang
        $datas->appends($request->all());

        return response()->json([
            'datas'      => $datas,
            'pagination' => $datas->links('pagination::bootstrap-5')->toHtml(),
        ]);
    }



    public function store(Request $request, DocumentReaderService $reader)
    {

        $request->validate(
            [
                'title' => 'required|string|max:255',
                'document' => 'required|file|mimes:txt,pdf,doc,docx|max:10240', // 10MB
            ],
            [
                'title.required'   => 'Vui lòng nhập tiêu đề tài liệu.',
                'title.string'     => 'Tiêu đề phải là chuỗi ký tự.',
                'title.max'        => 'Tiêu đề không được vượt quá 255 ký tự.',

                'document.required' => 'Vui lòng chọn tệp tài liệu để tải lên.',
                'document.file'    => 'Tệp không hợp lệ.',
                'document.mimes'   => 'Chỉ hỗ trợ các định dạng: TXT, PDF, DOC, DOCX.',
                'document.max'     => 'Dung lượng tệp tối đa là 10MB.',
            ]
        );

        $file = $request->file('document');
        // Di chuyển file vào public/documents
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('documents'), $filename);

        // Lấy path đầy đủ sau khi đã move
        $fullPath = public_path('documents/' . $filename);

        // Đọc nội dung từ file vừa lưu
        $content = $reader->read($fullPath);

        // Lưu DB
        $document = TrainingDocument::create([
            'training_set_id' =>  $request->training_set_id,
            'title'     => $request->title,
            'file_path' => 'documents/' . $filename, // lưu path tương đối để load qua asset()
            'content'   => $content,
        ]);


        return response()->json($document);
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
