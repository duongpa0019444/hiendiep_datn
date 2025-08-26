<?php

namespace App\Exports;

use App\Models\Score;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScoresExport implements FromCollection, WithHeadings
{
    protected $classId;
    protected $courseId;

    public function __construct($classId, $courseId)
    {
        $this->classId = $classId;
        $this->courseId = $courseId;
    }

    public function collection()
    {
        return Score::with('student', 'class.course')
            ->where('class_id', $this->classId)
            ->whereHas('class', function ($q) {
                $q->where('courses_id', $this->courseId);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'Mã học sinh'=> optional($item->student)->snake_case,
                    'Học sinh'   => optional($item->student)->name,
                    'Lớp'        => optional($item->class)->name,
                    'Khóa'       => optional($item->class->course)->name,
                    'Loại điểm'  => $item->score_type,
                    'Điểm'       => $item->score,
                    'Ngày'       => \Carbon\Carbon::parse($item->exam_date)->format('d/m/Y'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Học sinh', 'Lớp', 'Khóa', 'Điểm', 'Loại điểm', 'Ngày'];
    }
}


