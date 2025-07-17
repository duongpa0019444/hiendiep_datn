<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\teacher_salary_rules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeacherRulesController extends Controller
{
    public function details($id)
    {
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

    public function indexRules()
    {
        $today = Carbon::now();

        $users = DB::table('users as u')
            ->leftJoin('teacher_salary_rules as tsr', function ($join) use ($today) {
                $join->on('tsr.teacher_id', '=', 'u.id')
                    ->where(function ($query) use ($today) {
                        $query->whereNull('tsr.effective_date')
                            ->orWhere(function ($q) use ($today) {
                                $q->whereYear('tsr.effective_date', '=', $today->year)
                                    ->whereMonth('tsr.effective_date', '=', $today->month);
                            });
                    });
            })
            ->where('u.role', 'teacher')
            ->select('u.id', 'u.name', 'tsr.pay_rate', 'tsr.effective_date')
            ->get();

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function getRulesByTeacher($teacherId)
{
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
