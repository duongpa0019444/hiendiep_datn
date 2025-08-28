<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueStatisticsExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        return collect(DB::select("
            SELECT
                ? AS year,
                m.month,
                COALESCE(SUM(cp.amount), 0) AS total_revenue,
                COALESCE(COUNT(cp.student_id), 0) AS total_students
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
        ", [$this->year, $this->year]));
    }

    public function headings(): array
    {
        return [
            'Năm',
            'Tháng',
            'Tổng doanh thu',
            'Số lượng học viên đã đóng'
        ];
    }
}
