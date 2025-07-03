<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\news;
use App\Models\topics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class newsController extends Controller
{
    //

    public function index(Request $request)
    {
        $news = news::orderByDesc('created_at')->paginate(10);
        $statistics = [
            (object)[
                'total_news'          => news::count(),
                'total_public_news'   => news::where('is_visible', 1)->count(),
                'total_draft_news'    => news::where('publish_status', 'draft')->count(),
                'total_views'         => news::sum('views'),
                'total_topics'        => topics::count(),
                'total_featured'      => news::where('is_featured', 1)->count(),
                'total_homepage'      => news::where('show_on_homepage', 1)->count(),
            ]
        ];
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'news' => $news,
                'pagination' => $news->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.news.news-list', compact('news', 'statistics'));
    }



    public function filter(Request $request)
    {
        $news = $this->getFilterNews($request);
        $news->appends($request->all());
        return response()->json([
            'news' => $news,
            'pagination' => $news->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterNews(Request $request)
    {
        $query = news::query();

        // Từ khóa trong tiêu đề
        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo chủ đề
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Lọc theo loại (Tin tức / Sự kiện)
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Lọc theo trạng thái hiển thị
        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible);
        }

        // Lọc theo trạng thái xuất bản
        if ($request->filled('publish_status')) {
            $query->where('publish_status', $request->publish_status);
        }

        // Lọc theo hiển thị trang chủ
        if ($request->filled('show_on_homepage')) {
            $query->where('show_on_homepage', $request->show_on_homepage);
        }

        // Lọc theo nổi bật
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        return $query->with(['creator', 'topic'])->orderByDesc('created_at')->paginate($request->limit ?? 10);
    }



    public function create()
    {
        return view('admin.news.news-create-edit', ['isEdit' => false]);
    }


    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.news-create-edit', ['isEdit' => true, 'news' => $news]);
    }



    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'short_intro' => 'required|string|max:255',
            'full_content' => 'required|string',
            'topic_id' => 'required|exists:topics,id',
            'event_type' => 'required|in:news,event',
            'image_caption' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'is_visible' => 'required|boolean',
            'show_on_homepage' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'short_intro.required' => 'Giới thiệu ngắn là bắt buộc.',
            'short_intro.max' => 'Giới thiệu ngắn không được vượt quá 255 ký tự.',
            'full_content.required' => 'Nội dung đầy đủ là bắt buộc.',
            'topic_id.required' => 'Vui lòng chọn chủ đề.',
            'topic_id.exists' => 'Chủ đề đã chọn không hợp lệ.',
            'event_type.required' => 'Loại bài viết là bắt buộc.',
            'event_type.in' => 'Loại bài viết không hợp lệ.',
            'image_caption.max' => 'Chú thích ảnh không được vượt quá 255 ký tự.',
            'seo_title.max' => 'Tiêu đề SEO không được vượt quá 255 ký tự.',
            'seo_description.max' => 'Mô tả SEO không được vượt quá 255 ký tự.',
            'seo_keywords.max' => 'Từ khóa SEO không được vượt quá 255 ký tự.',
            'is_visible.required' => 'Vui lòng chọn trạng thái hiển thị.',
            'is_visible.boolean' => 'Trạng thái hiển thị không hợp lệ.',
            'show_on_homepage.required' => 'Vui lòng chọn hiển thị trang chủ.',
            'show_on_homepage.boolean' => 'Giá trị hiển thị trang chủ không hợp lệ.',
            'is_featured.required' => 'Vui lòng chọn trạng thái nổi bật.',
            'is_featured.boolean' => 'Trạng thái nổi bật không hợp lệ.',
            'image.required' => 'Hình ảnh là bắt buộc.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ cho phép các định dạng: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);



        $news = new news();
        $news->title = $request->title;
        $news->slug = str::slug($request->title);
        $news->topic_id = $request->topic_id;
        $news->event_type = $request->event_type;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $cleanName = Str::slug($originalName);
            $imageName = time() . '_' . $cleanName . '.' . $extension;
            $image->move(public_path('uploads/news'), $imageName);
            $news->image = 'uploads/news/' . $imageName;
        }
        $news->image_caption = $request->image_caption;
        $news->short_intro = $request->short_intro;
        $news->full_content = $request->full_content;
        $news->seo_title = $request->seo_title;
        $news->seo_description = $request->seo_description;
        $news->seo_keywords = $request->seo_keywords;
        $news->views = 0;
        $news->created_by = Auth::id();
        $news->updated_by = Auth::id();
        $news->is_visible = $request->is_visible;
        $news->show_on_homepage = $request->show_on_homepage;
        $news->is_featured = $request->is_featured;
        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Thêm bài viết thành công!');
    }



    public function updateToggle(Request $request)
    {
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;

        $news = news::findOrFail($id);
        $news->$field = $value;
        $news->save();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'short_intro' => 'required|string|max:255',
            'full_content' => 'required|string',
            'topic_id' => 'required|exists:topics,id',
            'event_type' => 'required|in:news,event',
            'image_caption' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'is_visible' => 'required|boolean',
            'show_on_homepage' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'short_intro.required' => 'Giới thiệu ngắn là bắt buộc.',
            'short_intro.max' => 'Giới thiệu ngắn không được vượt quá 255 ký tự.',
            'full_content.required' => 'Nội dung đầy đủ là bắt buộc.',
            'topic_id.required' => 'Vui lòng chọn chủ đề.',
            'topic_id.exists' => 'Chủ đề đã chọn không hợp lệ.',
            'event_type.required' => 'Loại bài viết là bắt buộc.',
            'event_type.in' => 'Loại bài viết không hợp lệ.',
            'image_caption.max' => 'Chú thích ảnh không được vượt quá 255 ký tự.',
            'seo_title.max' => 'Tiêu đề SEO không được vượt quá 255 ký tự.',
            'seo_description.max' => 'Mô tả SEO không được vượt quá 255 ký tự.',
            'seo_keywords.max' => 'Từ khóa SEO không được vượt quá 255 ký tự.',
            'is_visible.required' => 'Vui lòng chọn trạng thái hiển thị.',
            'is_visible.boolean' => 'Trạng thái hiển thị không hợp lệ.',
            'show_on_homepage.required' => 'Vui lòng chọn hiển thị trang chủ.',
            'show_on_homepage.boolean' => 'Giá trị hiển thị trang chủ không hợp lệ.',
            'is_featured.required' => 'Vui lòng chọn trạng thái nổi bật.',
            'is_featured.boolean' => 'Trạng thái nổi bật không hợp lệ.',
            'image.required' => 'Hình ảnh là bắt buộc.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ cho phép các định dạng: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);


        $news = news::findOrFail($id);

        $news->title = $request->title;
        $news->slug = str::slug($request->title);
        $news->topic_id = $request->topic_id;
        $news->event_type = $request->event_type;
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if (!empty($news->image) && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }

            // Thêm ảnh mới
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $cleanName = Str::slug($originalName);
            $imageName = time() . '_' . $cleanName . '.' . $extension;
            $image->move(public_path('uploads/news'), $imageName);
            $news->image = 'uploads/news/' . $imageName;
        }

        $news->image_caption = $request->image_caption;
        $news->short_intro = $request->short_intro;
        $news->full_content = $request->full_content;
        $news->seo_title = $request->seo_title;
        $news->seo_description = $request->seo_description;
        $news->seo_keywords = $request->seo_keywords;
        $news->updated_by = Auth::id();
        $news->is_visible = $request->is_visible;
        $news->show_on_homepage = $request->show_on_homepage;
        $news->is_featured = $request->is_featured;
        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Sửa bài viết thành công!');
    }

    public function delete($id)
    {
        $news = news::findOrFail($id);
        $news->delete();

        return response()->json(['success' => true]);
    }






    // Hiển thị danh sách bài viết đã xóa mềm
    public function trash(Request $request)
    {
        $news = news::onlyTrashed()->orderBy('deleted_at', 'desc')->with(['creator', 'topic'])->paginate(10);
        $totalDeleted = News::onlyTrashed()->count();
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'news' => $news,
                'pagination' => $news->links('pagination::bootstrap-5')->toHtml()
            ]);
        }
        return view('admin.news.news-trash', compact('news', 'totalDeleted'));
    }

    //Khôi phục
    public function restore($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);

        $news->restore();

        return redirect()->back()->with('success', 'Khôi phục bài viết thành công!');
    }

    //xóa vĩnh viễn
    public function forceDelete($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);

        // Xóa ảnh nếu có
        if (!empty($news->image) && File::exists(public_path($news->image))) {
            File::delete(public_path($news->image));
        }

        $news->forceDelete(); // Xóa vĩnh viễn khỏi DB

        return redirect()->back()->with('success', 'Xóa vĩnh viễn bài viết thành công!');
    }


    public function filterTrash(Request $request)
    {
        $news = $this->getFilterNewsTrash($request);
        $news->appends($request->all());
        return response()->json([
            'news' => $news,
            'pagination' => $news->links('pagination::bootstrap-5')->toHtml()
        ]);
    }
    private function getFilterNewsTrash(Request $request)
    {
        $query = News::onlyTrashed()
            ->with(['creator', 'topic'])
            ->orderBy('deleted_at', 'desc');

        // Lọc theo từ khóa trong tiêu đề
        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo chủ đề
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Lọc theo ngày xóa từ
        if ($request->filled('deleted_from')) {
            $query->whereDate('deleted_at', '>=', $request->deleted_from);
        }

        // Lọc theo ngày xóa đến
        if ($request->filled('deleted_to')) {
            $query->whereDate('deleted_at', '<=', $request->deleted_to);
        }

        return $query->paginate($request->limit ?? 10)->appends($request->all());
    }
}
