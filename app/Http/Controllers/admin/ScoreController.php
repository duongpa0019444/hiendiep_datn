<?php

namespace App\Http\Controllers\admin;

use App\Exports\ScoresExport;
use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\classStudent;
use App\Models\score;
use App\Models\User;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ScoreController extends Controller
{
    public function index()
    {
        $data = Classes::with('course')->get();
        return view('admin.scores.score', compact('data'));
    }

    public function scoreSearch(Request $request)
    {
        $query = trim($request->query('query'));
        if ($query) {
            $data = Classes::where('name', 'like', "%$query%")
                ->orWhereHas('course', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->with('course')
                ->get();

            return view('admin.scores.score', compact('data', 'query'));
        }
        return redirect()->route('admin.score'); // Chuyển hướng nếu không có query
    }

    public function detail($class_id, $course_id)
    {
        $data = score::with(['student', 'class.course'])->where('class_id', $class_id)->paginate(9);
        // dd($data);
        return view('admin.scores.score-detail', compact('data'));
    }

    public function add($class_id)
    {
        $data = ClassStudent::with('student')  // lấy quan hệ đến bảng users (học sinh)
            ->where('class_id', $class_id)
            ->get();

        return view('admin.scores.score-add', compact('data'));
    }

    public function store($class_id, Request $request)
    {

        $validated = $request->validate([
            'class_id'    => 'nullable',
            'student_id'  => 'required',
            'score_type'  => 'required|string|max:255|unique:scores,score_type',
            'score'       => 'required|numeric|min:0|max:10',
            'exam_date'   => 'required|date',
        ]);



        $validated['class_id'] = $class_id;
        Score::create($validated);

        $course_id = classes::find($class_id)?->courses_id;

        return redirect()->route('admin.score.detail', ['class_id' => $class_id, 'course_id' => $course_id])->with('success', 'Đã thêm điểm thành công!');
    }

    public function edit($class_id, $id)
    {
        $data = ClassStudent::with('student')
            ->where('class_id', $class_id)
            ->get();

        $score = score::find($id);

        return view('admin.scores.score-edit', compact('data', 'score'));
    }

    public function update($class_id, Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required',
            'score_type'  => 'required|string|max:255',
            'score'       => 'required|numeric|min:0|max:10',
            'exam_date'   => 'required|date',
        ]);

        $validated['class_id'] = $class_id;

        // Tìm điểm theo id truyền vào (nên truyền id)
        $score = Score::find($request->id);

        if (!$score) {
            return redirect()->back()->with('error', 'Không tìm thấy điểm để cập nhật.');
        }

        // Kiểm tra trùng loại điểm (loại trừ chính nó)
        $existing = Score::where('student_id', $request->student_id)
            ->where('class_id', $request->class_id)
            ->where('score_type', $request->score_type)
            ->where('id', '!=', $score->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Loại điểm này đã tồn tại.');
        }

        $score->update($validated);

        $course_id = classes::findOrFail($class_id)?->courses_id;

        return redirect()->route('admin.score.detail', [
            'class_id' => $class_id,
            'course_id' => $course_id
        ])->with('success', 'Đã cập nhật điểm thành công!');
    }




    public function export($classId, $courseId)
    {
        return Excel::download(new ScoresExport($classId, $courseId), 'bangdiem.xlsx');
    }
}
