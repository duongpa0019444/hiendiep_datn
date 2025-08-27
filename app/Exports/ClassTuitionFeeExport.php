<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassTuitionFeeExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        return DB::table('classes as c')
            ->leftJoin('course_payments as cp', 'cp.class_id', '=', 'c.id')
            ->select(
                'c.name as class_name',
                DB::raw("COUNT(DISTINCT CASE WHEN cp.status = 'paid'  THEN cp.student_id END) as paid_students"),
                DB::raw("COUNT(DISTINCT CASE WHEN cp.status = 'unpaid' THEN cp.student_id END) as unpaid_students"),
                DB::raw("COALESCE(SUM(CASE WHEN cp.status = 'paid'  THEN cp.amount END), 0) as total_paid_amount"),
                DB::raw("COALESCE(SUM(CASE WHEN cp.status = 'unpaid' THEN cp.amount END), 0) as total_unpaid_amount")
            )
            ->whereYear('c.created_at', $this->year)
            ->groupBy('c.id', 'c.name')
            ->orderBy('c.name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tên lớp',
            'Số học sinh đã đóng',
            'Số học sinh chưa đóng',
            'Tổng tiền đã đóng',
            'Tổng tiền chưa đóng',
        ];
    }
}
