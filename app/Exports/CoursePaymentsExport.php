<?php

namespace App\Exports;

use App\Models\CoursePayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoursePaymentsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = CoursePayment::with(['user', 'class', 'course']);

        if (!empty($this->filters['keyword'])) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->filters['keyword'] . '%');
            });
        }

        if (!empty($this->filters['class_id'])) {
            $query->where('class_id', $this->filters['class_id']);
        }

        if (!empty($this->filters['status_class'])) {
            $query->whereHas('class', function ($q) {
                $q->where('status', $this->filters['status_class']);
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['method'])) {
            $query->where('method', $this->filters['method']);
        }

        return $query->get()->map(function ($payment) {
            return [
                optional($payment->user)->name,
                optional($payment->class)->name,
                optional($payment->course)->name ?? '',
                optional($payment->payment_date)->format('d/m/Y H:i'),
                number_format($payment->amount, 0, ',', '.'),
                $payment->status === 'paid' ? 'Đã thanh toán' : ($payment->status === 'unpaid' ? 'Chưa thanh toán' : ''),
                $payment->method === 'Cash' ? 'Tiền mặt' : ($payment->method === 'Bank Transfer' ? 'Chuyển khoản' : ''),
                $payment->note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tên học sinh',
            'Lớp',
            'Khóa học',
            'Ngày thanh toán',
            'Số tiền (VNĐ)',
            'Trạng thái',
            'Phương thức',
            'Ghi chú',
        ];
    }
}
