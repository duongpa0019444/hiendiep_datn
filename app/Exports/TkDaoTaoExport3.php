<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TkDaoTaoExport3 implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {   
        $rows =$statusClassesCourses = DB::select("
            SELECT
                co.name AS course_name,
                SUM(CASE WHEN c.status = 'not_started' THEN 1 ELSE 0 END) AS lop_chua_bat_dau,
                SUM(CASE WHEN c.status = 'in_progress'  THEN 1 ELSE 0 END) AS lop_dang_dien_ra,
                SUM(CASE WHEN c.status = 'completed' THEN 1 ELSE 0 END) AS lop_da_hoan_thanh,
                COUNT(c.id) AS tong_lop
            FROM courses co
            LEFT JOIN classes c ON c.courses_id = co.id
            WHERE YEAR(c.created_at) = ?
            GROUP BY YEAR(c.created_at), co.id, co.name
            ORDER BY co.id;
        ", [$this->year]);

        return collect($rows); // phải convert sang Collection
    }

    public function headings(): array
    {
        return [
            'Khóa học', 'Lớp chưa băt đầu', 'Lớp đang diễn ra', 'Lớp đã hoàn thành', 'Tổng'
        ];
    }
}
