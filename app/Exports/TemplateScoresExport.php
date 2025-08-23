<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TemplateScoresExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly ?int $classId = null, private readonly ?int $courseId = null) {}

    public function headings(): array
    {
        return [
            'Mã sinh viên',
            'Tên học sinh',
            'Lớp',
            'Khóa học',
            'Loại điểm',
            'Điểm',
            'Ngày'
        ];
    }

    public function collection()
    {
        if ($this->classId) {
            $students = User::query()
                ->join('class_student', 'users.id', '=', 'class_student.student_id')
                ->join('classes', 'class_student.class_id', '=', 'classes.id')
                ->join('courses', 'courses.id', '=', 'classes.courses_id')
                ->where('classes.id', $this->classId)
                ->select('users.snake_case', 'users.name as user_name', 'classes.name as class_name', 'courses.name as course_name')
                ->get();

            return $students->map(function ($u) {
                return [
                    'Mã sinh viên' => $u->snake_case,
                    'Tên học sinh' => $u->user_name,
                    'Lớp'          => $u->class_name,
                    'Khóa học'     => $u->course_name ?? '',
                    'Loại'         => '',
                    'Điểm'         => '',
                    'Ngày'         => '', // YYYY-MM-DD
                ];
            });
        }

        return new Collection([
            ['Mã sinh viên' => '', 'Tên học sinh' => '', 'Lớp' => '', 'Khóa học' => '', 'Loại điểm' => '', 'Điểm' => '', 'Ngày' => ''],
            ['Mã sinh viên' => '', 'Tên học sinh' => '', 'Lớp' => '', 'Khóa học' => '', 'Loại điểm' => '', 'Điểm' => '', 'Ngày' => ''],
        ]);
    }

    public function map($row): array
    {
        return [
            $row['Mã sinh viên'] ?? '',
            $row['Tên học sinh'] ?? '',
            $row['Lớp'] ?? '',
            $row['Khóa học'] ?? '',
            $row['Loại điểm'] ?? '',
            $row['Điểm'] ?? '',
            $row['Ngày'] ?? '',
        ];
    }
}
