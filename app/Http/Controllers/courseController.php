<?php

namespace App\Http\Controllers;

use App\Models\lessions;
use App\Models\courses;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;


class CourseController  extends Controller
{


    public function index(Request $request)
    {
        $query = courses::query();

        // Lọc theo tên khóa học
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Lọc theo tháng tạo
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        // Sắp xếp theo giá
        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        // Lấy tất cả khóa học sau khi lọc (nếu bạn muốn phân trang, dùng ->paginate(10))
        $courses = $query->paginate(10);

        // Thống kê cơ bản
        $totalCourses = $courses->count();
        $totalSessions = $courses->sum('total_sessions');
        $totalRevenue = $courses->sum('price');

        // Số khóa học được tạo trong tháng hiện tại
        $coursesThisMonth = courses::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();

        // Nếu là AJAX request, trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'courses' => $courses,
                'pagination' => $courses->links('pagination::bootstrap-5')->toHtml()
            ]);
        }



        // Truyền tất cả dữ liệu về view
        return view('admin.course.course-list', compact(
            'courses',
            'totalCourses',
            'totalSessions',
            'totalRevenue',
            'coursesThisMonth'
        ));
    }

    public function show($id)
    {
        $lessions = Lessions::where('course_id', $id)->get(); // ✔ lấy theo điều kiện
        $course = courses::findOrFail($id);
        return view('admin.course.course-detail', compact('course', 'lessions'));
    }
    public function delete($id)
    {
        try {

            $course = courses::findOrFail($id);
            $course->delete();

            return redirect()->route('admin.course-list')->with('success', 'Xóa khóa học thành công');
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('admin.course-list')
                    ->with('error', 'Không thể xóa khóa học vì vẫn còn lớp học liên kết.');
            }
        }
    }
    public function add()
    {
        return view('admin.course.course-add');
    }
    public function create(Request $request)
    {
        $course = new courses();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/courses', $fileName, 'public');
            $course->image = $filePath;
        }
        $course->name = $request->input('name');
        $course->price = $request->input('price');
        $course->total_sessions = $request->input('total_sessions');
        $course->description = $request->input('description');
        $course->created_at = $request->input('created_at');
        $course->updated_at = $request->input('updated_at');
        $course->teaching_method = $request->input('teaching_method');
        $course->teaching_goals = $request->input('teaching_goals');

        $course->save();

        return redirect()->route('admin.course-list')->with('success', 'Thêm khóa học thành công');
    }

    public function edit($id)
    {
        $course = courses::find($id);
        return view('admin.course.course-edit', compact('course'));
    }
    public function update(Request $request, $id)
    {
        $course = courses::find($id);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/courses', $fileName, 'public');
            $course->image = $filePath;
        }
        $course->name = $request->input('name');
        $course->price = $request->input('price');
        $course->total_sessions = $request->input('total_sessions');
        $course->description = $request->input('description');
        $course->created_at = $request->input('created_at');
        $course->updated_at = $request->input('updated_at');
        $course->teaching_method = $request->input('teaching_method');
        $course->teaching_goals = $request->input('teaching_goals');
        $course->save();

        return redirect()->route('admin.course-list')->with('success', 'Cập nhật khóa học thành công');
    }

    public function deleteLession($id)
    {
        $lession = Lessions::findOrFail($id);
        $lession->delete();
        // return redirect()->route('admin.course-detail', ['id' => $course_id]);
        return redirect()->back()->with('success', 'Xóa khóa học thành công thành công ');
    }
    public function addLession($id)
    {
        $course = courses::findOrFail($id);
        return view('admin.course.lession-add', compact('id'));
    }

    public function createLession(Request $request, $id)
    {
        $lesson = new Lessions();
        $lesson->course_id = $id;
        $lesson->name = $request->input('name');
        $lesson->link_document = $request->input('link_document');
        $lesson->created_at = now();
        $lesson->updated_at = $request->input('updated_at');
        $lesson->save();

        // return redirect()->route('admin.course-detail', ['id' => $id])->with('success', 'Thêm bài học thành công!');
        return redirect()->route('admin.course-detail', ['id' => $id])->with('success', 'Thêm bài giảng thành công!');
    }
    public function editLession($course_id, $id)
    {
        // dd($course_id, $id);
        $course_id = $course_id;
        $lession = Lessions::findOrFail($id);
        return view('admin.course.lession-edit', ['id' => $id], compact('lession', 'course_id'));
    }
    public function updateLession(Request $request, $course_id, $id)
    {
        $course_id = $course_id;
        $lession = Lessions::findOrFail($id);
        $lession->name = $request->input('name');
        $lession->link_document = $request->input('link_document');
        $lession->updated_at = now();
        $lession->save();

        return redirect()->route('admin.course-detail', ['id' => $course_id])->with('success', 'Cập nhật bài học thành công!');
    }
}
