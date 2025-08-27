<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryStatisticsExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
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
        ", [$this->year]);

        return collect($dataTongLuong);

    }

    public function headings(): array
    {
        return [
            'Teacher ID',
            'Mã giáo viên',
            'Tên giáo viên',
            'Tháng 1',
            'Tháng 2',
            'Tháng 3',
            'Tháng 4',
            'Tháng 5',
            'Tháng 6',
            'Tháng 7',
            'Tháng 8',
            'Tháng 9',
            'Tháng 10',
            'Tháng 11',
            'Tháng 12',
            'Tổng cả năm'
        ];
    }
}
