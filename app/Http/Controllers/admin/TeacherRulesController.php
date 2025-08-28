<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\teacher_salary_rules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherRulesController extends Controller
{
    public function details($id)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }

        $items = DB::table('teacher_salary_rules as tsr')
            ->join('users as u', 'u.id', '=', 'tsr.teacher_id')
            ->where('tsr.teacher_id', $id)
            ->select('u.id', 'u.phone', 'tsr.pay_rate', 'tsr.effective_date')
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có dữ liệu chi tiết.']);
        }

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function indexRules(Request $request)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }
        if ($request->has('month')) {
            $today = Carbon::parse($request->month);
        } else {
            $today = Carbon::now();
        }

        $teacherId = $request->teacher_id;
        $keyword = $request->keyword;

        // Subquery: Lấy mức lương mới nhất theo effective_date <= hôm nay
        $latestSalarySub = DB::table('teacher_salary_rules as tsr1')
            ->select('tsr1.teacher_id', 'tsr1.pay_rate', 'tsr1.effective_date')
            ->whereRaw('tsr1.effective_date = (
            SELECT MAX(tsr2.effective_date)
            FROM teacher_salary_rules tsr2
            WHERE tsr2.teacher_id = tsr1.teacher_id
              AND tsr2.effective_date <= ?
        )', [$today]);

        $query = DB::table('users as u')
            ->leftJoinSub($latestSalarySub, 'tsr', 'tsr.teacher_id', '=', 'u.id')
            ->where('u.role', 'teacher');

        if ($teacherId) {
            $query->where('u.id', $teacherId);
        } elseif ($keyword) {
            $query->where('u.name', 'like', '%' . $keyword . '%');
        }

        // Lọc theo thứ tự
        $sort = $request->query('sort');
        if ($sort) {
            switch ($sort) {
                case 'created_at_desc':
                    $query->orderBy('u.created_at', 'desc');
                    break;

                case 'created_at_asc':
                    $query->orderBy('u.created_at', 'asc');
                    break;

                case 'name_asc':
                    $query->orderBy('u.name', 'asc');
                    break;

                case 'name_desc':
                    $query->orderBy('u.name', 'desc');
                    break;

                default:
                    $query->orderBy('u.id', 'desc');
                    break;
            }
        } else {
            $query->orderBy('u.id', 'desc');
        }


        $users = $query->select('u.id', 'u.name', 'tsr.pay_rate', 'tsr.effective_date')->paginate(5);

        $allTeachers = DB::table('users')->where('role', 'teacher')->select('id', 'name')->orderBy('name','desc')->get();

        return view('admin.teacher_salaries.teacher-rules', compact('users', 'allTeachers', 'teacherId', 'keyword'));
    }

    public function searchTeacher(Request $request)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }
        $keyword = $request->q;

        $teachers = DB::table('users')
            ->where('role', 'teacher')
            ->where('name', 'like', '%' . $keyword . '%')
            ->select('id', 'name')
            ->limit(10)
            ->get();

        return response()->json($teachers);
    }



    public function getRulesByTeacher($teacherId)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }
        $rules = teacher_salary_rules::where('teacher_id', $teacherId)
            ->orderBy('effective_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    public function store(Request $request)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }

         $data = $request->all();

        // loại bỏ dấu phẩy
        $data['pay_rate'] = str_replace('.', '', $data['pay_rate']);
      

        $request->replace($data);

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'pay_rate' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $teacherId = $request->teacher_id;
            $newRate = $request->pay_rate;
            $newEffective = $request->effective_date;

            // Lấy bảng lương mới nhất (chưa có end_pay_rate)
            $latest = DB::table('teacher_salary_rules')
                ->where('teacher_id', $teacherId)
                ->whereNull('end_pay_rate')
                ->orderByDesc('effective_date')
                ->first();

            if ($latest) {
                if ($latest->pay_rate == $newRate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mức lương mới phải khác mức lương hiện tại.'
                    ]);
                }

                if ($newEffective <= $latest->effective_date) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ngày hiệu lực phải lớn hơn mức lương hiện tại.'
                    ]);
                }

                // ✅ Cập nhật ngày kết thúc cho bản ghi cũ
                DB::table('teacher_salary_rules')
                    ->where('id', $latest->id)
                    ->update([
                        'end_pay_rate' => Carbon::parse($newEffective)->subDay()->toDateString()
                    ]);
            }

            // ✅ Insert bản ghi mới
            DB::table('teacher_salary_rules')->insert([
                'teacher_id' => $teacherId,
                'pay_rate' => $newRate,
                'effective_date' => $newEffective,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tạo bảng lương mới thành công.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo bảng lương: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tạo bảng lương.'
            ]);
        }
    }
}
