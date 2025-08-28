<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TkDaoTaoExport1 implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {   
        $rows = DB::select("
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
            LEFT JOIN dang_ky d ON d.course_id = c.id
            GROUP BY c.id, c.name
            ORDER BY c.id
        ", [$this->year]);

        return collect($rows); // phải convert sang Collection
    }

    public function headings(): array
    {
        return [
            'Khóa học',
            'Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6',
            'Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12',
            'Tổng cả năm'
        ];
    }
}
