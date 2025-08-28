<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ClassTuitionFeeExport;
use App\Exports\LaiLoStatisticsExport;
use App\Exports\RevenueStatisticsExport;
use App\Http\Controllers\Controller;
use App\Models\classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\SalaryStatisticsExport;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ThongKeController extends Controller
{
    public function index()
    {
        return view('admin.thongkedaotao');
    }

    //Hàm trả về số học sinh đăng ký theo năm dưới dạng JSON.
    public function registerStudent($year = null)
    {
        $registerStudent = DB::select("
            WITH dang_ky AS (
                SELECT
                    cp.course_id,
                    MONTH(cp.created_at) AS thang,
                    COUNT(cp.student_id) AS so_hoc_sinh
                FROM course_payments cp
                WHERE YEAR(cp.created_at) = ?
                GROUP BY cp.course_id, MONTH(cp.created_at)
            )
            SELECT
                c.id AS course_id,
                c.name AS course_name,
                COALESCE(SUM(CASE WHEN d.thang = 1  THEN d.so_hoc_sinh END), 0) AS Thang1,
                COALESCE(SUM(CASE WHEN d.thang = 2  THEN d.so_hoc_sinh END), 0) AS Thang2,
                COALESCE(SUM(CASE WHEN d.thang = 3  THEN d.so_hoc_sinh END), 0) AS Thang3,
                COALESCE(SUM(CASE WHEN d.thang = 4  THEN d.so_hoc_sinh END), 0) AS Thang4,
                COALESCE(SUM(CASE WHEN d.thang = 5  THEN d.so_hoc_sinh END), 0) AS Thang5,
                COALESCE(SUM(CASE WHEN d.thang = 6  THEN d.so_hoc_sinh END), 0) AS Thang6,
                COALESCE(SUM(CASE WHEN d.thang = 7  THEN d.so_hoc_sinh END), 0) AS Thang7,
                COALESCE(SUM(CASE WHEN d.thang = 8  THEN d.so_hoc_sinh END), 0) AS Thang8,
                COALESCE(SUM(CASE WHEN d.thang = 9  THEN d.so_hoc_sinh END), 0) AS Thang9,
                COALESCE(SUM(CASE WHEN d.thang = 10 THEN d.so_hoc_sinh END), 0) AS Thang10,
                COALESCE(SUM(CASE WHEN d.thang = 11 THEN d.so_hoc_sinh END), 0) AS Thang11,
                COALESCE(SUM(CASE WHEN d.thang = 12 THEN d.so_hoc_sinh END), 0) AS Thang12,
                COALESCE(SUM(d.so_hoc_sinh), 0) AS TongCaNam
            FROM courses c
            LEFT JOIN dang_ky d
                ON d.course_id = c.id
            GROUP BY c.id, c.name
            ORDER BY c.id;

        ", [$year]);

        $data = [];
        foreach ($registerStudent as $item) {
            $data[] = [
                'name' => $item->course_name,
                'data' => [
                    $item->Thang1,
                    $item->Thang2,
                    $item->Thang3,
                    $item->Thang4,
                    $item->Thang5,
                    $item->Thang6,
                    $item->Thang7,
                    $item->Thang8,
                    $item->Thang9,
                    $item->Thang10,
                    $item->Thang11,
                    $item->Thang12
                ]
            ];
        }

        //Top 3 khóa học có số học sinh đăng ký nhiều nhất trong năm
        $top3Courses = DB::select("
            WITH dang_ky AS (
                SELECT
                    YEAR(cp.created_at) AS nam,
                    MONTH(cp.created_at) AS thang,
                    cp.course_id,
                    COUNT(cp.student_id) AS so_hoc_sinh
                FROM course_payments cp
                WHERE YEAR(cp.created_at) = ?
                GROUP BY YEAR(cp.created_at), MONTH(cp.created_at), cp.course_id
                ),
                xep_hang AS (
                SELECT
                    d.nam,
                    d.thang,
                    d.course_id,
                    d.so_hoc_sinh,
                    ROW_NUMBER() OVER (
                    PARTITION BY d.nam, d.thang
                    ORDER BY d.so_hoc_sinh DESC
                    ) AS rn
            FROM dang_ky d
            )
            SELECT
            x.thang,
            MAX(CASE WHEN x.rn = 1 THEN CONCAT(c.name, ' (', x.so_hoc_sinh, ')') END) AS Top1,
            MAX(CASE WHEN x.rn = 2 THEN CONCAT(c.name, ' (', x.so_hoc_sinh, ')') END) AS Top2,
            MAX(CASE WHEN x.rn = 3 THEN CONCAT(c.name, ' (', x.so_hoc_sinh, ')') END) AS Top3
            FROM xep_hang x
            JOIN courses c ON c.id = x.course_id
            WHERE x.rn <= 3
            GROUP BY x.thang
            ORDER BY x.thang;


        ", [$year]);



        return response()->json([
            'data' => $data,
            'top3Courses' => $top3Courses
        ]);
    }


    //Hàm thống kê buổi dạy của giáo viên
    public function thongkebuoiday($year = null)
    {
        $year = $year ?? date('Y');
        $dataBuoiday = DB::select("
            WITH lich_day AS (
                SELECT
                    s.teacher_id,
                    YEAR(s.date) AS nam,
                    MONTH(s.date) AS thang,
                    COUNT(*) AS so_buoi_day
                FROM schedules s
                JOIN users u ON u.id = s.teacher_id AND u.role = 'teacher'
                WHERE YEAR(s.date) = ?
                GROUP BY s.teacher_id, YEAR(s.date), MONTH(s.date)
                )
                SELECT
                u.id AS teacher_id,
                u.name AS teacher_name,
                COALESCE(MAX(CASE WHEN l.thang = 1 THEN l.so_buoi_day END), 0) AS Th1,
                COALESCE(MAX(CASE WHEN l.thang = 2 THEN l.so_buoi_day END), 0) AS Th2,
                COALESCE(MAX(CASE WHEN l.thang = 3 THEN l.so_buoi_day END), 0) AS Th3,
                COALESCE(MAX(CASE WHEN l.thang = 4 THEN l.so_buoi_day END), 0) AS Th4,
                COALESCE(MAX(CASE WHEN l.thang = 5 THEN l.so_buoi_day END), 0) AS Th5,
                COALESCE(MAX(CASE WHEN l.thang = 6 THEN l.so_buoi_day END), 0) AS Th6,
                COALESCE(MAX(CASE WHEN l.thang = 7 THEN l.so_buoi_day END), 0) AS Th7,
                COALESCE(MAX(CASE WHEN l.thang = 8 THEN l.so_buoi_day END), 0) AS Th8,
                COALESCE(MAX(CASE WHEN l.thang = 9 THEN l.so_buoi_day END), 0) AS Th9,
                COALESCE(MAX(CASE WHEN l.thang = 10 THEN l.so_buoi_day END), 0) AS Th10,
                COALESCE(MAX(CASE WHEN l.thang = 11 THEN l.so_buoi_day END), 0) AS Th11,
                COALESCE(MAX(CASE WHEN l.thang = 12 THEN l.so_buoi_day END), 0) AS Th12
                FROM users u
                LEFT JOIN lich_day l
                    ON u.id = l.teacher_id
                WHERE u.role = 'teacher'
                GROUP BY u.id, u.name
                ORDER BY u.id;

        ", [$year]);

        $data = [];
        foreach ($dataBuoiday as $item) {
            $data[] = [
                'name' => $item->teacher_name,
                'data' => [
                    $item->Th1,
                    $item->Th2,
                    $item->Th3,
                    $item->Th4,
                    $item->Th5,
                    $item->Th6,
                    $item->Th7,
                    $item->Th8,
                    $item->Th9,
                    $item->Th10,
                    $item->Th11,
                    $item->Th12
                ]
            ];
        }
        return response()->json(['data' => $data]);
    }


    //Hàm thống kê trạng thái lớp học
    public function statusClasses($year = null)
    {
        $year = $year ?? date('Y');
        $statusClasses = DB::select("
            SELECT
                YEAR(c.created_at) AS nam,
                c.status,
                COUNT(*) AS so_lop,
                ROUND(100.0 * COUNT(*) / t.tong, 1) AS ti_le_phan_tram
            FROM classes c
            JOIN (
                SELECT YEAR(created_at) AS nam, COUNT(*) AS tong
                FROM classes
                WHERE YEAR(created_at) = ?
                GROUP BY YEAR(created_at)
            ) t ON t.nam = YEAR(c.created_at)
            WHERE YEAR(c.created_at) = ?
            GROUP BY YEAR(c.created_at), c.status, t.tong
            ORDER BY nam, c.status;
        ", [$year, $year]);

        foreach ($statusClasses as $item) {
            if ($item->status === 'not_started') {
                $chuabatdau = $item->so_lop;
            } elseif ($item->status === 'in_progress') {
                $dangdienra = $item->so_lop;
            } elseif ($item->status === 'completed') {
                $dahoanthanh = $item->so_lop;
            }
        }


        //Top 3 khóa nhiều lớp nhất
        $top3Courses = DB::select("
            SELECT *
                FROM (
                    SELECT
                        YEAR(c.created_at) AS nam,
                        co.id AS course_id,
                        co.name AS course_name,
                        COUNT(c.id) AS so_lop,
                        ROW_NUMBER() OVER (
                            PARTITION BY YEAR(c.created_at)
                            ORDER BY COUNT(c.id) DESC
                        ) AS xep_hang
                    FROM classes c
                    JOIN courses co ON co.id = c.courses_id
                    WHERE YEAR(c.created_at) = ?
                    GROUP BY YEAR(c.created_at), co.id, co.name
                ) ranked
                WHERE xep_hang <= 3
                ORDER BY nam, so_lop DESC;
        ", [$year]);
        $top3 = [];
        $dataTop3 = [];
        foreach ($top3Courses as $item) {
            $top3[] = $item->course_name;
            $dataTop3[] = $item->so_lop;
        }

        $bottom3Courses = DB::select("
            SELECT *
                FROM (
                    SELECT
                        YEAR(c.created_at) AS nam,
                        co.id AS course_id,
                        co.name AS course_name,
                        COUNT(c.id) AS so_lop,
                        ROW_NUMBER() OVER (
                            PARTITION BY YEAR(c.created_at)
                            ORDER BY COUNT(c.id) ASC
                        ) AS xep_hang
                    FROM classes c
                    JOIN courses co ON co.id = c.courses_id
                    WHERE YEAR(c.created_at) = ?
                    GROUP BY YEAR(c.created_at), co.id, co.name
                ) ranked
                WHERE xep_hang <= 3
                ORDER BY nam, so_lop ASC;
        ", [$year]);
        $bottom3 = [];
        $dataBottom3 = [];
        foreach ($bottom3Courses as $item) {
            $bottom3[] = $item->course_name;
            $dataBottom3[] = $item->so_lop;
        }

        //Thống kê tổng trạng thái lớp học theo khóa
        $statusClassesCourses = DB::select("
            SELECT
                YEAR(c.created_at) AS nam,
                co.id AS courses_id,
                co.name AS course_name,
                SUM(CASE WHEN c.status = 'not_started' THEN 1 ELSE 0 END) AS lop_chua_bat_dau,
                SUM(CASE WHEN c.status = 'in_progress'  THEN 1 ELSE 0 END) AS lop_dang_dien_ra,
                SUM(CASE WHEN c.status = 'completed' THEN 1 ELSE 0 END) AS lop_da_hoan_thanh,
                COUNT(c.id) AS tong_lop
            FROM courses co
            LEFT JOIN classes c ON c.courses_id = co.id
            WHERE YEAR(c.created_at) = ?
            GROUP BY YEAR(c.created_at), co.id, co.name
            ORDER BY nam, co.id;
        ", [$year]);


        return response()->json([
            'labelsClasses' => ['Chưa bắt đầu', 'Đang diễn ra', 'Đã hoàn thành'],
            'statusClasses' => [$chuabatdau ?? 0, $dangdienra ?? 0, $dahoanthanh ?? 0],
            'top3' => $top3,
            'dataTop3' => $dataTop3,
            'bottom3' => $bottom3,
            'dataBottom3' => $dataBottom3,
            'statusClassesCourses' => $statusClassesCourses
        ]);
    }





    //Hàm trả về JSON chứa số lượng học sinh theo từng lớp.
    public function classStudentCounts($year = null)
    {
        $rows = classes::query()
            ->select('classes.id', 'classes.name', DB::raw('COUNT(users.id) as student_count'))
            ->leftJoin('class_student as cs', function ($join) {
                $join->on('cs.class_id', '=', 'classes.id')
                    ->whereNull('cs.deleted_at');
            })
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'cs.student_id')
                    ->where('users.role', '=', 'student')
                    ->whereNull('users.deleted_at');
            })
            ->whereNull('classes.deleted_at')
            ->whereYear('classes.created_at', $year)
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('classes.created_at', 'desc')
            ->paginate(10); // 10 dòng mỗi trang

        // Tách labels (tên lớp) và counts (số học sinh)
        $labels = $rows->pluck('name')->values();
        $counts = $rows->pluck('student_count')->map(fn($n) => (int) $n)->values();


        return response()->json([
            'labels' => $labels,
            'counts' => $counts,
            'pagination' => $rows->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    // Hàm lấy điểm trung bình theo lớp
    public function classAverageScores($year = null)
    {
        $rows = DB::table('classes as c')
            ->join('scores as s', 's.class_id', '=', 'c.id')
            ->whereNotNull('s.score')
            ->whereYear('c.created_at', $year)
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('c.created_at')
            ->select('c.id', 'c.name', DB::raw('ROUND(AVG(s.score),1) as avg_score'))
            ->paginate(10);

        return response()->json([
            'labels' => $rows->pluck('name'),
            'scores' => $rows->pluck('avg_score')->map(fn($v) => (float)$v),
            'pagination' => $rows->links('pagination::bootstrap-5')->toHtml()

        ]);
    }







    public function studyStatistics()
    {
        return view('admin.thongketaichinh');
    }

    //Quỹ lương giáo viên theo tháng
    public function salarystatistics($year = null)
    {
        $year = $year ?? date('Y');
        $dataTongLuong = DB::select("
            SELECT
                u.id         AS teacher_id,
                u.snake_case AS teacher_code,
                u.name       AS teacher_name,
                COALESCE(SUM(CASE WHEN ts.month = 1  THEN ts.total_salary END), 0) AS T1,
                COALESCE(SUM(CASE WHEN ts.month = 2  THEN ts.total_salary END), 0) AS T2,
                COALESCE(SUM(CASE WHEN ts.month = 3  THEN ts.total_salary END), 0) AS T3,
                COALESCE(SUM(CASE WHEN ts.month = 4  THEN ts.total_salary END), 0) AS T4,
                COALESCE(SUM(CASE WHEN ts.month = 5  THEN ts.total_salary END), 0) AS T5,
                COALESCE(SUM(CASE WHEN ts.month = 6  THEN ts.total_salary END), 0) AS T6,
                COALESCE(SUM(CASE WHEN ts.month = 7  THEN ts.total_salary END), 0) AS T7,
                COALESCE(SUM(CASE WHEN ts.month = 8  THEN ts.total_salary END), 0) AS T8,
                COALESCE(SUM(CASE WHEN ts.month = 9  THEN ts.total_salary END), 0) AS T9,
                COALESCE(SUM(CASE WHEN ts.month = 10 THEN ts.total_salary END), 0) AS T10,
                COALESCE(SUM(CASE WHEN ts.month = 11 THEN ts.total_salary END), 0) AS T11,
                COALESCE(SUM(CASE WHEN ts.month = 12 THEN ts.total_salary END), 0) AS T12,
                COALESCE(SUM(ts.total_salary), 0) AS total_year
            FROM users u
            LEFT JOIN teacher_salaries ts
            ON ts.teacher_id = u.id
            AND ts.year = ?
            WHERE u.role = 'teacher' AND u.deleted_at IS NULL
            GROUP BY u.id, u.snake_case, u.name
            ORDER BY u.name

        ", [$year]);

        $data = [];
        foreach ($dataTongLuong as $item) {
            $data[] = [
                'name' => $item->teacher_name,
                'data' => [
                    $item->T1,
                    $item->T2,
                    $item->T3,
                    $item->T4,
                    $item->T5,
                    $item->T6,
                    $item->T7,
                    $item->T8,
                    $item->T9,
                    $item->T10,
                    $item->T11,
                    $item->T12
                ]
            ];
        }

        return response()->json(['data' => $data]);
    }
    public function exportSalaryStatistics($year = null)
    {
        try {
            $year = $year ?? date('Y');
            return Excel::download(new SalaryStatisticsExport($year), "bao_cao_quy_luong_$year.xlsx");
        } catch (Exception $e) {
            // Ghi log để debug
            Log::error('Export Salary Error: ' . $e->getMessage());

            // Trả về JSON báo lỗi (cho Ajax)
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xuất báo cáo!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    //Tổng daonh thu học phí theo tháng
    public function revenuestatistics($year = null)
    {
        $dataDoanhThu = DB::select("
            SELECT
                ? AS year,
                m.month,
                COALESCE(SUM(cp.amount), 0) AS total_revenue,
                COALESCE(COUNT(DISTINCT cp.student_id), 0) AS total_students
            FROM (
                SELECT 1 AS month UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
                UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
            ) AS m
            LEFT JOIN course_payments cp
                ON MONTH(cp.payment_date) = m.month
                AND YEAR(cp.payment_date) = ?
                AND cp.status = 'paid'
            GROUP BY m.month
            ORDER BY m.month
        ", [$year, $year]);
        $data = [];
        foreach ($dataDoanhThu as $key => $item) {
            $data[$key] = $item->total_revenue;
        }
        return response()->json(['data' => $data]);
    }
    // Xuất báo cáo doanh thu học phí theo tháng
    public function exportRevenueStatistics($year = null)
    {
        try {
            $year = $year ?? date('Y');
            return Excel::download(
                new RevenueStatisticsExport($year),
                "bao_cao_doanh_thu_$year.xlsx"
            );
        } catch (\Exception $e) {
            Log::error("Lỗi xuất báo cáo doanh thu: " . $e->getMessage());
            return response()->json([
                'error' => 'Không thể xuất báo cáo doanh thu'
            ], 500);
        }
    }


    //Tình trạng đóng học phí theo lớp
    public function classTuitionFee($year = null)
    {
        $dataHocPhiLop = DB::table('classes as c')
            ->leftJoin('course_payments as cp', 'cp.class_id', '=', 'c.id')
            ->select(
                'c.id as class_id',
                'c.name as class_name',
                DB::raw("COUNT(DISTINCT CASE WHEN cp.status = 'paid'  THEN cp.student_id END) as paid_students"),
                DB::raw("COUNT(DISTINCT CASE WHEN cp.status = 'unpaid' THEN cp.student_id END) as unpaid_students"),
                DB::raw("COALESCE(SUM(CASE WHEN cp.status = 'paid'  THEN cp.amount END), 0) as total_paid_amount"),
                DB::raw("COALESCE(SUM(CASE WHEN cp.status = 'unpaid' THEN cp.amount END), 0) as total_unpaid_amount")
            )
            ->whereYear('c.created_at', $year)
            ->groupBy('c.id', 'c.name')
            ->orderBy('c.name')
            ->paginate(10);

        return response()->json([
            'data' => $dataHocPhiLop,
            'pagination' => $dataHocPhiLop->links('pagination::bootstrap-5')->toHtml()
        ]);
    }
    // Xuất báo cáo Excel
    public function exportClassTuitionFee($year = null)
    {
        $year = $year ?? date('Y');
        return Excel::download(new ClassTuitionFeeExport($year), "bao_cao_hoc_phi_theo_lop_$year.xlsx");
    }


    //So sánh Doanh thu & Chi phí lương (Lãi/Lỗ)
    public function laiLoStatistics($year = null)
    {
        $year = $year ?? date('Y');
        $dataLaiLo = DB::select("
            WITH RECURSIVE months AS (
                SELECT 1 AS thang
                UNION ALL
                SELECT thang + 1 FROM months WHERE thang < 12
                ),
                doanh_thu AS (
                SELECT
                    MONTH(cp.payment_date) AS thang,
                    SUM(cp.amount) AS tong_doanh_thu
                FROM course_payments cp
                WHERE YEAR(cp.payment_date) = ?
                GROUP BY MONTH(cp.payment_date)
                ),
                luong AS (
                SELECT
                    ts.month AS thang,
                    SUM(ts.total_salary) AS tong_luong_gv
            FROM teacher_salaries ts
            WHERE ts.year = ?
            GROUP BY ts.month
            )
            SELECT
            ? AS nam,
            m.thang,
            IFNULL(d.tong_doanh_thu, 0) AS tong_doanh_thu,
            IFNULL(l.tong_luong_gv, 0) AS tong_luong_gv,
            (IFNULL(d.tong_doanh_thu, 0) - IFNULL(l.tong_luong_gv, 0)) AS lai_lo
            FROM months m
            LEFT JOIN doanh_thu d ON d.thang = m.thang
            LEFT JOIN luong l ON l.thang = m.thang
            ORDER BY m.thang;
        ", [$year, $year, $year]);


        return response()->json(['data' => $dataLaiLo]);
    }

     // Xuất báo cáo Excel Lãi/Lỗ
    public function exportLaiLoStatistics($year = null)
    {
        $year = $year ?? date('Y');
        return Excel::download(new LaiLoStatisticsExport($year), "bao_cao_lai_lo_$year.xlsx");
    }
}
