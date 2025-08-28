<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\staff_salary_rules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffRulesController extends Controller
{
    public function details($id)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }

        $items = DB::table('staff_salary_rules as tsr')
            ->join('users as u', 'u.id', '=', 'tsr.staff_id')
            ->where('tsr.staff_id', $id)
            ->select('u.id', 'u.phone', 'tsr.base_salary', 'tsr.salary_coefficient','tsr.insurance', 'tsr.start_pay_rate')
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

        $staffId = $request->staff_id;
        $keyword = $request->keyword;

        // Subquery: Lấy mức lương mới nhất theo effective_date <= hôm nay
        $latestSalarySub = DB::table('staff_salary_rules as tsr1')
            ->select('tsr1.staff_id', 'tsr1.base_salary', 'tsr1.salary_coefficient', 'tsr1.insurance', 'tsr1.start_pay_rate')
            ->whereRaw('tsr1.start_pay_rate = (
            SELECT MAX(tsr2.start_pay_rate)
            FROM staff_salary_rules tsr2
            WHERE tsr2.staff_id = tsr1.staff_id
              AND tsr2.start_pay_rate <= ?
        )', [$today]);

        $query = DB::table('users as u')
            ->leftJoinSub($latestSalarySub, 'tsr', 'tsr.staff_id', '=', 'u.id')
            ->where('u.role', 'staff');

        if ($staffId) {
            $query->where('u.id', $staffId);
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


        $users = $query->select('u.id', 'u.name', 'tsr.base_salary', 'tsr.salary_coefficient','tsr.insurance', 'tsr.start_pay_rate')->paginate(5);

        $allstaffs = DB::table('users')->where('role', 'staff')->select('id', 'name')->orderBy('name','desc')->get();

        return view('admin.staff_salaries.staff-rules', compact('users', 'allstaffs', 'staffId', 'keyword'));
    }

    public function searchstaff(Request $request)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }
        $keyword = $request->q;

        $staffs = DB::table('users')
            ->where('role', 'staff')
            ->where('name', 'like', '%' . $keyword . '%')
            ->select('id', 'name')
            ->limit(10)
            ->get();

        return response()->json($staffs);
    }



    public function getRulesBystaff($staffId)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
        }
        $rules = staff_salary_rules::where('staff_id', $staffId)
            ->orderBy('start_pay_rate', 'desc')
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
        $data['base_salary'] = str_replace('.', '', $data['base_salary']);
      
        $request->replace($data);

        $request->validate([
            'staff_id' => 'required|exists:users,id',
            'base_salary' => 'required|numeric|min:0',
            'salary_coefficient' => 'required|numeric|min:0',
            'insurance' => 'required|numeric|min:0|max:30',
            'start_pay_rate' => 'required|date',
        ]);


        try {
            DB::beginTransaction();

            $staffId = $request->staff_id;
            $NewBase = $request->base_salary;
            $NewCoefficient = $request->salary_coefficient;
            $NewInsurance = $request->insurance;
            $newEffective = $request->start_pay_rate;

            // Lấy bảng lương mới nhất (chưa có end_pay_rate)
            $latest = DB::table('staff_salary_rules')
                ->where('staff_id', $staffId)
                ->whereNull('end_pay_rate')
                ->orderByDesc('start_pay_rate')
                ->first();

            if ($latest) {
            

                if($latest->salary_coefficient == $NewCoefficient) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hệ số lương mới phải khác hệ số lương hiện tại.'
                    ]);
                }

            

                if ($newEffective <= $latest->start_pay_rate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ngày hiệu lực phải lớn hơn mức lương hiện tại.'
                    ]);
                }

                // ✅ Cập nhật ngày kết thúc cho bản ghi cũ
                DB::table('staff_salary_rules')
                    ->where('id', $latest->id)
                    ->update([
                        'end_pay_rate' => Carbon::parse($newEffective)->subDay()->toDateString()
                    ]);
            }

            // ✅ Insert bản ghi mới
            DB::table('staff_salary_rules')->insert([
                'staff_id' => $staffId,
                'base_salary' => $NewBase,
                'salary_coefficient' => $NewCoefficient,
                'insurance' => $NewInsurance,
                'start_pay_rate' => $newEffective,
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
