<?php

namespace App\Http\Controllers\client;

use App\Exports\ScoresExport;
use App\Exports\ScoresImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\classStudent;
use App\Models\coursePayment;
use App\Models\Quizzes;
use App\Models\notification;
use App\Models\Schedule;
use App\Models\score;
use App\Models\User as ModelsUser;
use Database\Seeders\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function information()
    {
        if (Auth::user()->role == "student") {

            $user = Auth::user();
            $userId = $user->id;
            $unPaymentInfo = coursePayment::where('student_id', $userId)
                ->where('status', 'unpaid')
                ->get();
            $classId = null;
            if ($user->role === 'student') {
                $classId = DB::table('class_student')
                    ->where('student_id', $user->id)
                    ->value('class_id');
            }

            // Lấy danh sách thông báo phù hợp
            $notifications = notification::where(function ($q) use ($user, $classId) {
                $q->where('target_role', 'all')
                    ->orWhere('target_role', $user->role);

                if ($user->role === 'student' && $classId) {
                    $q->orWhere(function ($sub) use ($classId) {
                        $sub->where('target_role', 'class')
                            ->where('class_id', $classId);
                    });
                }
            })
                ->orderByDesc('created_at')
                // ->paginate(3);
                ->limit(3)
                ->get();

            return view('client.accounts.students.dashboard', compact('unPaymentInfo', 'notifications'));
        } elseif (Auth::user()->role == "teacher") {


            $user = Auth::user();
            $userId = $user->id;
            $unPaymentInfo = coursePayment::where('student_id', $userId)
                ->where('status', 'unpaid')
                ->get();
            $classId = null;
            if ($user->role === 'student') {
                $classId = DB::table('class_student')
                    ->where('student_id', $user->id)
                    ->value('class_id');
            }

            // Lấy danh sách thông báo phù hợp
            $notifications = notification::where(function ($q) use ($user, $classId) {
                $q->where('target_role', 'all')
                    ->orWhere('target_role', $user->role);

                if ($user->role === 'student' && $classId) {
                    $q->orWhere(function ($sub) use ($classId) {
                        $sub->where('target_role', 'class')
                            ->where('class_id', $classId);
                    });
                }
            })
                ->orderByDesc('created_at')
                // ->paginate(3);
                ->limit(3)
                ->get();

            return view('client.accounts.teachers.dashboard', compact('unPaymentInfo', 'notifications'));
        }
    }





    public function schedule()
    {
        if (Auth::user()->role == "student") {
            return view('client.accounts.students.schedule');
        } elseif (Auth::user()->role == "teacher") {
            return view('client.accounts.teachers.schedule');
        }
    }

    public function score()
    {
        if (Auth::user()->role == "student") {
            $data = score::with('class.course')->where('student_id', Auth::user()->id)->paginate(5);
            return view('client.accounts.students.score', compact('data'));
        } elseif (Auth::user()->role == "teacher") {

            $data = Classes::with('course')
                ->join('schedules', 'schedules.class_id', '=', 'classes.id')
                ->where('schedules.teacher_id', Auth::id())
                ->select('classes.*')
                ->distinct()
                ->paginate(18);

            return view('client.accounts.teachers.score', compact('data'));
        }
    }
    // Cho giáo viên
    public function scoreSearch(Request $request)
    {
        $query = trim($request->query('queryScoreClient'));

        if ($query) {
            $data = Classes::with('course')
                ->join('schedules', 'schedules.class_id', '=', 'classes.id')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->where('schedules.teacher_id', Auth::id())
                ->where(function ($q) use ($query) {
                    $q->where('classes.name', 'like', "%$query%")
                        ->orWhere('courses.name', 'like', "%$query%");
                })
                ->select('classes.*') // Lấy dữ liệu từ bảng classes
                ->distinct()
                ->paginate(18);

            return view('client.accounts.teachers.score', compact('data', 'query'));
        }


        return redirect()->route('client.score');
    }


    public function Scoredetail($class_id, $course_id)
    {
        $data = score::with(['student', 'class.course'])
            ->orWhere('class_id', $class_id)
            ->paginate(5);

        return view('client.accounts.teachers.score-detail', compact('data'));
    }

    public function ScoredetailSearch(Request $request, $class_id, $course_id)
    {
        $query = trim($request->query('searchScoreStudent'));

        if ($query) {
            $data = Score::with(['student', 'class.course'])
                ->where('class_id', $class_id)
                ->whereHas('student', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->paginate(5);

            return view('client.accounts.teachers.score-detail', compact('data', 'query'));
        }

        return redirect()->route('client.score.detail', [$class_id, $course_id]);
    }


    public function Scoreadd($class_id)
    {
        $data = ClassStudent::with('student')  // lấy quan hệ đến bảng users (học sinh)
            ->where('class_id', $class_id)
            ->get();
        // lấy thêm tên khóa học từ bảng classes
        $class = classes::where('id', $class_id)->select('courses_id', 'id')->first();

        return view('client.accounts.teachers.score-add', compact('data', 'class'));
    }

    public function Scorestore($class_id, Request $request)
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

        return redirect()->route('client.score.detail', ['class_id' => $class_id, 'course_id' => $course_id])->with('success', 'Đã thêm điểm thành công!');
    }

    public function Scoreedit($class_id, $id)
    {
        $data = ClassStudent::with('student')
            ->where('class_id', $class_id)
            ->get();

        $score = score::find($id);

        $class = classes::where('id', $class_id)->select('courses_id', 'id')?->first();


        return view('client.accounts.teachers.score-edit', compact('data', 'score', 'class'));
    }

    public function Scoreupdate($class_id, Request $request)
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

        return redirect()->route('client.score.detail', [
            'class_id' => $class_id,
            'course_id' => $course_id
        ])->with('success', 'Đã cập nhật điểm thành công!');
    }

    public function Scoredelete($id)
    {
        score::find($id)->delete();
        return redirect()->back()->with('success', 'Xóa điểm thành công!');
    }

    public function Scoreimport(Request $request)
    {
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Vui lòng chọn file để nhập điểm.');
        }

        $errors = [];

        try {
            Excel::import(new ScoresImport($errors), $request->file('file'));

            if (count($errors)) {
                return redirect()->back()
                    ->with('error', 'Có lỗi khi nhập điểm, kiểm tra lại dữ liệu.')
                    ->with('import_errors', $errors);
            }

            return redirect()->back()->with('success', 'Nhập điểm thành công');
        } catch (\Throwable $e) {
            Log::error('Lỗi import điểm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'File lỗi định dạng hoặc hệ thống.');
        }
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



    public function Scoreexport($classId, $courseId)
    {
        return Excel::download(new ScoresExport($classId, $courseId), 'bangdiem.xlsx');
    }



    public function quizz()
    {
        if (Auth::user()->role == "student") {
            $studentId =  Auth::user()->id;
            $quizzesDone = DB::table('quiz_attempts')
                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                ->leftJoin('users as u', 'quizzes.created_by', '=', 'u.id')
                ->where('quiz_attempts.user_id', $studentId)
                ->groupBy(
                    'quizzes.id',
                    'quizzes.title',
                    'quizzes.status',
                    'quizzes.class_id',
                    'quizzes.created_by',
                    'quizzes.updated_at',
                    'u.name'
                )
                ->select(
                    'quizzes.id',
                    'quizzes.title',
                    'quizzes.status',
                    'quizzes.class_id',
                    'quizzes.created_by',
                    'quizzes.updated_at',
                    'u.name as creator_name',
                    DB::raw('COUNT(quiz_attempts.id) as attempt_count'),
                    DB::raw('MAX(quiz_attempts.submitted_at) as last_submitted_at'),
                    DB::raw('AVG(quiz_attempts.score) as avg_score')
                )
                ->orderBy('quizzes.updated_at', 'desc')
                ->get();


            $assignedQuizzes = DB::table('class_student as cs')
                ->join('classes as c', 'cs.class_id', '=', 'c.id')
                ->join('quizzes as q', 'q.class_id', '=', 'c.id')
                ->leftJoin('questions as ques', 'q.id', '=', 'ques.quiz_id')
                ->leftJoin('sentence_questions as sq', 'q.id', '=', 'sq.quiz_id')
                ->leftJoin('users as u', 'q.created_by', '=', 'u.id')
                ->where('cs.student_id', $studentId)
                ->where('q.status', 'published')
                ->groupBy(
                    'q.id',
                    'q.title',
                    'q.status',
                    'q.class_id',
                    'q.created_by',
                    'q.updated_at',
                    'c.name',
                    'u.name'
                )
                ->select(
                    'q.id',
                    'q.title',
                    'q.status',
                    'q.class_id',
                    'q.created_by',
                    'q.updated_at',
                    'c.name as class_name',
                    'u.name as creator_name',
                    DB::raw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as `total_questions`')
                )
                ->orderBy('q.updated_at', 'desc')
                ->get();

            return view('client.accounts.students.quizz', compact('quizzesDone', 'assignedQuizzes'));
        } elseif (Auth::user()->role == "teacher") {

            $queryBase = DB::table('quizzes as q')
                ->leftJoin('classes as c', 'q.class_id', '=', 'c.id')
                ->leftJoin('users as u', 'q.created_by', '=', 'u.id')
                ->leftJoin('questions as ques', 'q.id', '=', 'ques.quiz_id')
                ->leftJoin('sentence_questions as sq', 'q.id', '=', 'sq.quiz_id')
                ->select(
                    'q.id',
                    'q.title',
                    'q.status',
                    'q.class_id',
                    'q.duration_minutes',
                    'q.created_by',
                    'q.updated_at',
                    'q.deleted_at',
                    'c.name as class_name',
                    'u.name as creator_name',
                    DB::raw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as total_questions')
                )
                ->where('q.created_by', Auth::user()->id)
                ->groupBy(
                    'q.id',
                    'q.title',
                    'q.status',
                    'q.class_id',
                    'q.duration_minutes',
                    'q.created_by',
                    'q.updated_at',
                    'q.deleted_at',
                    'c.name',
                    'u.name'
                );

            // Lấy tất cả quiz
            $quizzesAll = (clone $queryBase)->whereNull('q.deleted_at')->orderBy('q.updated_at', 'desc')->get();

            // Lấy quiz đã xuất bản
            $quizzesPublished = (clone $queryBase)
                ->where('q.status', 'published')
                ->whereNull('q.deleted_at')
                ->orderBy('q.updated_at', 'desc')
                ->get();

            // Lấy quiz lưu nháp
            $quizzesDraft = (clone $queryBase)
                ->where('q.status', 'draft')
                ->whereNull('q.deleted_at')
                ->orderBy('q.updated_at', 'desc')
                ->get();

            // Lấy quiz đã xóa
            $quizzesTrashed = (clone $queryBase)
                ->whereNotNull('q.deleted_at')
                ->orderBy('q.updated_at', 'asc')
                ->get();

            return view('client.accounts.teachers.quizz', compact('quizzesAll', 'quizzesPublished', 'quizzesDraft', 'quizzesTrashed'));
        }
    }


    public function account()
    {
        if (Auth::user()->role == "student") {


            $user = ModelsUser::with('classes.course')->find(Auth::user()->id);

            // Tên tất cả các khóa học (không phân loại)
            $courses = $user->classes->pluck('course.name')->implode(', ');

            // Lấy khóa học đang học
            $inProgressClasses = $user->classes->filter(function ($class) {
                $course = $class->course;
                return $course && $class->status == 'in_progress';
            });
            $inProgressCourseNames = $inProgressClasses->pluck('course.name')->implode(', ');

            // Lấy các lớp có khóa học đã hoàn thành
            $completedClasses = $user->classes->filter(function ($class) {
                $course = $class->course;
                return $course && $class->status == 'completed';
            });

            // Lấy tên khóa học đã hoàn thành
            $completedCourseNames = $completedClasses->pluck('course.name')->implode(', ');




            // dd($user->classes->pluck('name')->toArray());
            return view('client.accounts.students.account', compact('user', 'courses', 'inProgressCourseNames', 'completedCourseNames'));
        } elseif (Auth::user()->role == "teacher") {

            $user = ModelsUser::with('classes.course')->find(Auth::user()->id);
            $classNames = Schedule::where('teacher_id', Auth::user()->id)
                ->join('classes', 'schedules.class_id', '=', 'classes.id')

                ->select('classes.name')
                ->distinct()
                ->pluck('name');


            return view('client.accounts.teachers.account', compact('user', 'classNames'));
        }
    }
    public function editAccount()
    {
        if (Auth::user()->role == "student") {

            $info = ModelsUser::find(Auth::user()->id);
            return view('client.accounts.students.edit-account', compact('info'));
        } elseif (Auth::user()->role == "teacher") {
            $info = ModelsUser::find(Auth::user()->id);
            return view('client.accounts.teachers.edit-account', compact('info'));
        }
    }

    public function updateAccount(Request $request)
    {
        $user = ModelsUser::find(Auth::user()->id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,name,' . $user->id,
            'email'      => 'nullable|string||min:6',
            'phone'      => 'nullable|digits_between:8,20',
            'gender'     => 'nullable|in:boy,girl',
            'birth_date' => 'nullable|date',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $destinationPath = public_path('uploads/avatar');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $validated['avatar'] = 'uploads/avatar/' . $filename;
        }


        $user->update($validated);

        return redirect()->route('client.account')->with('success', 'Cập nhật thông tin tài khoản thành công');
    }

    public function changePassword(Request $request)
    {


        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:8'],
            'new_password_confirmation' => 'required|min:8'
        ]);

        $user = ModelsUser::find(Auth::user()->id);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('client.account')->with('success', 'Đổi mật khẩu thành công!');
    }
}
