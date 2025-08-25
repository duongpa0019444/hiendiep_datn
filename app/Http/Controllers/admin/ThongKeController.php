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
    public function studyStatistics()
    {
        return view('admin.thongketaichinh');
    }

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
}
