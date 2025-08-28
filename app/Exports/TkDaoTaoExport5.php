<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TkDaoTaoExport5 implements FromCollection, WithHeadings
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
            ROUND(AVG(s.score), 1) AS avg_score
        FROM classes c
        JOIN scores s ON s.class_id = c.id
        WHERE s.score IS NOT NULL
          AND YEAR(c.created_at) = ?
        GROUP BY c.id, c.name
        ORDER BY c.created_at DESC
    ", [$this->year]);

        return collect($rows);
    }


    public function headings(): array
    {
        return [
            'Lớp học',
            'Điểm trung bình'
        ];
    }
}
