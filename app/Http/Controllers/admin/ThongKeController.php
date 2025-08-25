<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index()
    {
        return view('admin.thongkedaotao');
    }

    /**
     * Hàm trả về JSON chứa số lượng học sinh theo từng lớp.
     * - Join 3 bảng: classes, class_student, users.
     * - Lọc các bản ghi bị xóa mềm (deleted_at IS NULL).
     * - Chỉ tính user có role = 'student'.
     */
    public function classStudentCounts(Request $request)
    {
        $rows = DB::table('classes as c')
            ->leftJoin('class_student as cs', function ($join) {
                $join->on('cs.class_id', '=', 'c.id')
                    ->whereNull('cs.deleted_at'); // bỏ học sinh đã bị xóa mềm trong class_student
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'cs.student_id')
                    ->where('u.role', '=', 'student')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('c.deleted_at')
            ->groupBy('c.id', 'c.name')
            ->orderBy('c.id')
            ->select('c.id', 'c.name', DB::raw('COUNT(u.id) as student_count'))
            ->get();

        // Tách labels (tên lớp) và counts (số học sinh)
        $labels = $rows->pluck('name')->values();
        $counts = $rows->pluck('student_count')->map(fn($n) => (int) $n)->values();


        return response()->json([
            'labels' => $labels,
            'counts' => $counts,
            'total' => $rows->count(),
            'page' => 1,
            'per_page' => $rows->count(),
        ]);
    }

    // Hàm lấy điểm trung bình theo lớp
    public function classAverageScores()
    {
        $rows = DB::table('classes as c')
            ->join('scores as s', 's.class_id', '=', 'c.id')
            ->whereNotNull('s.score')
            ->groupBy('c.id', 'c.name')
            ->orderBy('c.id')
            ->select('c.id', 'c.name', DB::raw('ROUND(AVG(s.score),1) as avg_score'))
            ->get();

        return response()->json([
            'labels' => $rows->pluck('name'),
            'scores' => $rows->pluck('avg_score')->map(fn($v) => (float)$v),
        ]);
    }

    public function studyStatistics()
    {
        return view('admin.thongketaichinh');
    }
}
