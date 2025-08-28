<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\news;
use App\Models\topics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class topicsController extends Controller
{
    //
    public function index(Request $request)
    {
        $topics = topics::with('creator')->orderByDesc('created_at')->paginate(10);
         // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'topics' => $topics,
                'pagination' => $topics->links('pagination::bootstrap-5')->toHtml()
            ]);
        }
        return view('admin.topics.topics-list', compact('topics'));
    }

    public function filter(Request $request)
    {
        $topics = $this->getFilterNews($request);
        $topics->appends($request->all());
        return response()->json([
            'topics' => $topics,
            'pagination' => $topics->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterNews(Request $request)
    {
        $query = topics::query();

        // Từ khóa trong tiêu đề
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        return $query->with('creator')->orderByDesc('created_at')->paginate($request->limit ?? 10);
    }


    public function create()
    {
        return view('admin.topics.topics-create-edit',['isEdit' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'name' => 'required|string|max:255|unique:topics,name',

        ], [
            'name.required' => 'Tên chủ đề là bắt buộc.',
            'name.string' => 'Tên chủ đề phải là chuỗi ký tự.',
            'name.max' => 'Tên chủ đề không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'name.unique' => 'Tên chủ đề đã tồn tại.',

        ]);


        $topic = topics::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ]);

        $this->logAction(
            'create',
            topics::class,
            $topic->id,
            Auth::user()->name . ' đã tạo chủ đề: ' . $topic->name
        );
        return redirect()->route('admin.topics.index')->with('success', 'Chủ đề đã được tạo thành công!');
    }

    public function edit($id)
    {
        $topic = topics::findOrFail($id);
        return view('admin.topics.topics-create-edit',['isEdit' => true, 'topic' => $topic]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('topics', 'name')->ignore($id), // Loại trừ chính nó
            ],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên chủ đề là bắt buộc.',
            'name.string' => 'Tên chủ đề phải là chuỗi.',
            'name.max' => 'Tên chủ đề không được quá 255 ký tự.',
            'name.unique' => 'Tên chủ đề đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi.',
        ]);

        $topic = topics::findOrFail($id);
        $topic->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_by' => Auth::user()->id,
            'updated_at' => now()
        ]);

        $this->logAction(
            'update',
            topics::class,
            $topic->id,
            Auth::user()->name . ' đã cập nhật chủ đề: ' . $topic->name
        );

        return redirect()->route('admin.topics.index')->with('success', 'Chủ đề đã được cập nhật thành công!');
    }

    public function delete($id)
    {
        $topic = topics::findOrFail($id);
        $this->logAction(
            'delete',
            topics::class,
            $topic->id,
            Auth::user()->name . ' đã xóa chủ đề: ' . $topic->name
        );
        $topic->delete();
        return redirect()->back()->with('success', 'Chủ đề đã được xóa thành công!');
    }



    //Thùng rác
    public function trash(Request $request)
    {
        $topics = topics::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);

        // Kiểm tra nếu là AJAX request
         if ($request->ajax()) {
            return response()->json([
                'topics' => $topics,
                'pagination' => $topics->links('pagination::bootstrap-5')->toHtml()
            ]);
        }
        return view('admin.topics.topics-trash', compact('topics'));

    }

    public function filterTrash(Request $request)
    {
        $topics = $this->getFilterNewsTrash($request);
        $topics->appends($request->all());
        return response()->json([
            'topics' => $topics,
            'pagination' => $topics->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterNewsTrash(Request $request)
    {
        $query = topics::onlyTrashed();

        // Từ khóa trong tiêu đề
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        return $query->with('creator')->orderByDesc('deleted_at')->paginate($request->limit ?? 10);
    }

    public function restore($id){
        $topic = topics::onlyTrashed()->findOrFail($id);
        $topic->restore();
        $this->logAction(
            'update',
            topics::class,
            $topic->id,
            Auth::user()->name . ' đã khôi phục chủ đề: ' . $topic->name
        );
        return redirect()->back()->with('success', 'Khôi phục chủ đề thành công!');

    }

    public function forceDelete($id){
        $news = news::where('topic_id', $id)->get();
        if ($news->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa chủ đề này vì nó đang chứa các bài viết.');
        }
        $topic = topics::onlyTrashed()->findOrFail($id);
        $this->logAction(
            'delete',
            topics::class,
            $topic->id,
            Auth::user()->name . ' đã xóa vĩnh viễn chủ đề: ' . $topic->name
        );
        $topic->forceDelete();
        return redirect()->back()->with('success', 'Xóa vĩnh viễn chủ đề thành công!');
    }
}
