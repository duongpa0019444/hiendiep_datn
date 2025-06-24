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



    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'pay_rate' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
        ]);

        try {
            // Lấy bảng lương mới nhất của giáo viên đó
            $latest = DB::table('teacher_salary_rules')
                ->where('teacher_id', $request->teacher_id)
                ->orderByDesc('effective_date')
                ->first();

            // Nếu đã có bảng lương trước đó
            if ($latest) {
                // Kiểm tra nếu pay_rate giống
                if ($latest->pay_rate == $request->pay_rate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mức lương mới phải khác mức lương hiện tại.'
                    ]);
                }

                // Kiểm tra nếu ngày hiệu lực không lớn hơn
                if ($request->effective_date <= $latest->effective_date) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ngày hiệu lực phải lớn hơn lần trước.'
                    ]);
                }
            }

            // Insert bản ghi mới
            DB::table('teacher_salary_rules')->insert([
                'teacher_id' => $request->teacher_id,
                'pay_rate' => $request->pay_rate,
                'effective_date' => $request->effective_date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo bảng lương mới thành công.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo bảng lương: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tạo bảng lương.'
            ]);
        }
    }
}
