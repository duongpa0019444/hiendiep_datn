<?php

namespace App\Http\Controllers\admin;

use App\Exports\ScoresExport;
use App\Exports\ScoresImport;
use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\classStudent;
use App\Models\score;
use App\Models\User;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        // lấy thêm tên khóa học từ bảng classes
        $class = classes::where('id',$class_id)->select('courses_id', 'name')->first();

        return view('admin.scores.score-add', compact('data','class'));
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

        $class = classes::where('id',$class_id)->select('courses_id', 'name')?->first();


        return view('admin.scores.score-edit', compact('data', 'score', 'class'));
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


    public function import(Request $request)
    {
        Excel::import(new ScoresImport, $request->file('file'));
        return back()->with('success', 'Đã nhập điểm thành công!');
    }

    public static function parseExcelDate($value): ?string
    {
        try {
            // Nếu là object DateTime (Excel kiểu date chuẩn)
            if ($value instanceof \DateTimeInterface) {
                return \Carbon\Carbon::instance($value)->format('Y-m-d');
            }

            // Nếu là số serial Excel
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-d-m');
            }

            // Nếu là chuỗi có dấu /
            if (strpos($value, '/') !== false) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-d-m');
            }

            // Nếu là chuỗi có dấu -
            if (strpos($value, '-') !== false) {
                return \Carbon\Carbon::createFromFormat('Y-m-d', trim($value))->format('Y-d-m');
            }

            // Cuối cùng thử auto parse
            return \Carbon\Carbon::parse($value)->format('Y-d-m');
        } catch (\Throwable $e) {
            Log::warning("❌ Lỗi parse ngày: [$value] - " . $e->getMessage());
            return null;
        }
    }



    public function export($classId, $courseId)
    {
        return Excel::download(new ScoresExport($classId, $courseId), 'bangdiem.xlsx');
    }
}
