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
use Illuminate\Support\Carbon;
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


            //Lớp đang học

            $classes = DB::table('class_student as cs')
                ->join('classes as c', 'cs.class_id', '=', 'c.id')
                ->join('courses as co', 'c.courses_id', '=', 'co.id')
                ->join('schedules as s', 'c.id', '=', 's.class_id')
                ->join('users as u', 'cs.student_id', '=', 'u.id')
                ->join('users as t', 's.teacher_id', '=', 't.id')
                ->where('cs.student_id', $userId)
                ->where('cs.deleted_at', null)
                ->where('c.status', '!=', 'completed')
                ->select([
                    'c.id as class_id',
                    'u.name as student_name',
                    'c.name as class_name',
                    'c.status as class_status',
                    'co.name as course_name',
                    't.name as teacher_name',
                ])
                ->groupBy('u.name', 'c.name', 'c.status', 'co.name', 't.name', 'c.id')
                ->orderBy('c.created_at', 'asc')
                ->get();


            $attendance = DB::select("
                SELECT
                    u.name AS student_name,
                    c.name AS class_name,
                    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) AS student_sessions_present,
                    SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) AS student_sessions_late,
                    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) AS student_sessions_absent,
                    SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) AS student_sessions_excused
                FROM
                    class_student cs
                    INNER JOIN classes c ON cs.class_id = c.id
                    INNER JOIN users u ON cs.student_id = u.id
                    LEFT JOIN schedules s ON c.id = s.class_id
                    LEFT JOIN attendances a ON s.id = a.schedule_id AND a.user_id = ?
                WHERE
                    c.id = ?
                    AND cs.student_id = ?
                    AND c.deleted_at IS NULL
                    AND cs.deleted_at IS NULL
                GROUP BY
                    u.id, u.name, c.id, c.name

            ", [$userId, $classes[0]->class_id, $userId]);

            $hoctaps = $attendance[0];
            return view('client.accounts.students.dashboard', compact('unPaymentInfo', 'notifications', 'classes', 'hoctaps'));
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


            $todaySchedules = DB::table('schedules as s')
                ->join('classes as c', 's.class_id', '=', 'c.id')
                ->whereDate('s.date', Carbon::today())
                ->where('s.teacher_id', $userId)
                ->select(
                    'c.name as class_name',
                    's.start_time',
                    's.end_time',
                    DB::raw("DATE_FORMAT(s.date, '%d/%m/%Y') as formatted_date")
                )
                ->get();


            $upcomingSchedules = DB::table('schedules as s')
                ->join('classes as c', 's.class_id', '=', 'c.id')
                ->whereDate('s.date', '>', Carbon::today()) // chỉ lấy lịch sau hôm nay
                ->where('s.teacher_id', $userId)
                ->orderBy('s.date', 'asc')
                ->select(
                    'c.name as class_name',
                    's.start_time',
                    's.end_time',
                    DB::raw("DATE_FORMAT(s.date, '%d/%m/%Y') as formatted_date")
                )->limit(3)
                ->get();


            $overview = $this->OverviewTeacher(Carbon::now()->month, Carbon::now()->year, new Request());


            return view('client.accounts.teachers.dashboard', compact('unPaymentInfo', 'notifications', 'todaySchedules', 'upcomingSchedules', 'overview'));
        }
    }



    public function schedule()
    {
        $userId = Auth::user()->id;

        if (Auth::user()->role == "student") {
            $schedules = DB::table('schedules')
                ->join('class_student', 'schedules.class_id', '=', 'class_student.class_id')
                ->join('classes', 'schedules.class_id', '=', 'classes.id')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->join('users', 'schedules.teacher_id', '=', 'users.id')
                ->where('class_student.student_id', $userId)
                ->select(
                    DB::raw('CASE schedules.day_of_week
                        WHEN "Mon" THEN "Thứ 2"
                        WHEN "Tue" THEN "Thứ 3"
                        WHEN "Wed" THEN "Thứ 4"
                        WHEN "Thu" THEN "Thứ 5"
                        WHEN "Fri" THEN "Thứ 6"
                        WHEN "Sat" THEN "Thứ 7"
                        WHEN "Sun" THEN "Chủ nhật"
                    END as day_of_week'),
                    'schedules.date',
                    'schedules.start_time',
                    'schedules.end_time',
                    'courses.name as course_name',
                    'users.name as teacher_name'
                )
                ->paginate(10);

            // Trả về view cho học sinh với dữ liệu lịch học
            return view('client.accounts.students.schedule', [
                'schedules' => $schedules
            ]);
        } elseif (Auth::user()->role == "teacher") {
            $schedules = DB::table('schedules')
                ->join('classes', 'schedules.class_id', '=', 'classes.id')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->join('users', 'schedules.teacher_id', '=', 'users.id')
                ->where('schedules.teacher_id', $userId)
                ->select(
                    DB::raw('CASE schedules.day_of_week
                        WHEN "Mon" THEN "Thứ 2"
                        WHEN "Tue" THEN "Thứ 3"
                        WHEN "Wed" THEN "Thứ 4"
                        WHEN "Thu" THEN "Thứ 5"
                        WHEN "Fri" THEN "Thứ 6"
                        WHEN "Sat" THEN "Thứ 7"
                        WHEN "Sun" THEN "Chủ nhật"
                    END as day_of_week'),   
                    'schedules.id as schedule_id',
                    'schedules.date',
                    'schedules.start_time',
                    'schedules.end_time',
                    'courses.name as course_name',
                    'users.name as teacher_name'
                )
                ->paginate(10);

            return view('client.accounts.teachers.schedule', [
                'schedules' => $schedules
            ]);
        } else {
            // Xử lý trường hợp vai trò không hợp lệ
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập lịch học.');

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
            return view('client.accounts.students.account');
        } elseif (Auth::user()->role == "teacher") {


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



    public function getHoctaps($class_id)
    {
        $userId = Auth::user()->id;
        $hoctaps = DB::select("
                SELECT
                    u.name AS student_name,
                    c.name AS class_name,
                    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) AS student_sessions_present,
                    SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) AS student_sessions_late,
                    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) AS student_sessions_absent,
                    SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) AS student_sessions_excused
                FROM
                    class_student cs
                    INNER JOIN classes c ON cs.class_id = c.id
                    INNER JOIN users u ON cs.student_id = u.id
                    LEFT JOIN schedules s ON c.id = s.class_id
                    LEFT JOIN attendances a ON s.id = a.schedule_id AND a.user_id = ?
                WHERE
                    c.id = ?
                    AND cs.student_id = ?
                    AND c.deleted_at IS NULL
                    AND cs.deleted_at IS NULL
                GROUP BY
                    u.id, u.name, c.id, c.name;

            ", [$userId, $class_id, $userId]);


        return response()->json($hoctaps[0]);
    }

    public function OverviewTeacher($month, $year, Request $request)
    {
        $userId = Auth::user()->id;
        $result = DB::table('schedules as s')
            ->join('classes as c', 's.class_id', '=', 'c.id')
            ->join('class_student as cs', 'c.id', '=', 'cs.class_id')
            ->where('s.teacher_id', $userId)
            ->whereMonth('s.date', $month)
            ->whereYear('s.date', $year)
            ->whereNull('c.deleted_at')
            ->whereNull('cs.deleted_at')
            ->selectRaw('
                COUNT(DISTINCT c.id) AS total_classes,
                COUNT(DISTINCT CASE WHEN s.status = 1 THEN s.id END) AS total_sessions,
                COUNT(DISTINCT cs.student_id) AS total_students
            ')->first();
        if($request->ajax()) {
            return response()->json([
                'total_classes' => $result->total_classes,
                'total_sessions' => $result->total_sessions,
                'total_students' => $result->total_students
            ]);
        }
        return $result;
    }
}
