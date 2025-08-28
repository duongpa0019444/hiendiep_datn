<?php

namespace App\Exports;

use App\Models\classes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TkDaoTaoExport4 implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        $rows = DB::select("
    SELECT 
        c.name,
        COALESCE(COUNT(u.id), 0) AS student_count
    FROM classes c
    LEFT JOIN class_student cs 
        ON cs.class_id = c.id 
        AND cs.deleted_at IS NULL
    LEFT JOIN users u 
        ON u.id = cs.student_id
        AND u.role = 'student'
        AND u.deleted_at IS NULL
    WHERE c.deleted_at IS NULL
      AND YEAR(c.created_at) = ?
    GROUP BY c.id, c.name
    ORDER BY c.created_at DESC
", [$this->year]);


        return collect($rows); // phải convert sang Collection
    }

    public function headings(): array
    {
        return [
            'Tên khóa học',
            'Số học sinh trong lớp'
        ];
    }
}
