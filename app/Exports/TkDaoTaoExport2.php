<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TkDaoTaoExport2 implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {   
        $rows = DB::select("
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
                u.name AS teacher_name,
                COALESCE(SUM(CASE WHEN l.thang = 1 THEN l.so_buoi_day END), 0) AS Th1,
                COALESCE(SUM(CASE WHEN l.thang = 2 THEN l.so_buoi_day END), 0) AS Th2,
                COALESCE(SUM(CASE WHEN l.thang = 3 THEN l.so_buoi_day END), 0) AS Th3,
                COALESCE(SUM(CASE WHEN l.thang = 4 THEN l.so_buoi_day END), 0) AS Th4,
                COALESCE(SUM(CASE WHEN l.thang = 5 THEN l.so_buoi_day END), 0) AS Th5,
                COALESCE(SUM(CASE WHEN l.thang = 6 THEN l.so_buoi_day END), 0) AS Th6,
                COALESCE(SUM(CASE WHEN l.thang = 7 THEN l.so_buoi_day END), 0) AS Th7,
                COALESCE(SUM(CASE WHEN l.thang = 8 THEN l.so_buoi_day END), 0) AS Th8,
                COALESCE(SUM(CASE WHEN l.thang = 9 THEN l.so_buoi_day END), 0) AS Th9,
                COALESCE(SUM(CASE WHEN l.thang = 10 THEN l.so_buoi_day END), 0) AS Th10,
                COALESCE(SUM(CASE WHEN l.thang = 11 THEN l.so_buoi_day END), 0) AS Th11,
                COALESCE(SUM(CASE WHEN l.thang = 12 THEN l.so_buoi_day END), 0) AS Th12
                FROM users u
                LEFT JOIN lich_day l
                    ON u.id = l.teacher_id
                WHERE u.role = 'teacher'
                GROUP BY u.id, u.name
                ORDER BY u.id;

        ", [$this->year]);

        return collect($rows); // phải convert sang Collection
    }

    public function headings(): array
    {
        return [
            'Giáo viên',
            'Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6',
            'Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'
        ];
    }
}
