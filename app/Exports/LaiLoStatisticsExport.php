<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaiLoStatisticsExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        $year = $this->year;

        $data = DB::select("
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
            ORDER BY m.thang
        ", [$year, $year, $year]);

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Năm',
            'Tháng',
            'Tổng doanh thu',
            'Tổng lương giáo viên',
            'Lãi/Lỗ',
        ];
    }
}
