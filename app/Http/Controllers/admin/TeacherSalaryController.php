<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\teacher_salaries;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeacherSalaryController extends Controller
{
   public function index()
{
    $month = Carbon::now()->month;
    $year = Carbon::now()->year;

    $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString(); // YYYY-MM-01
    $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();     // YYYY-MM-31

    $salaries = DB::table('teacher_salaries as ts')
        ->join('users as u', 'u.id', '=', 'ts.teacher_id')
        ->leftJoin('teacher_salary_rules as tsr', function ($join) use ($startOfMonth, $endOfMonth) {
            $join->on('ts.teacher_id', '=', 'tsr.teacher_id')
                ->where('tsr.effective_date', '<=', $endOfMonth)
                ->where(function ($q) use ($startOfMonth) {
                    $q->whereNull('tsr.end_pay_rate')
                      ->orWhere('tsr.end_pay_rate', '>=', $startOfMonth);
                });
        })
        ->select(
            'ts.*',
            'u.name as teacher_name',
            'u.phone as teacher_phone',
            'tsr.pay_rate'
        )
        ->where('ts.month', $month)
        ->where('ts.year', $year)
        ->get();
    
    // Các phần còn lại giữ nguyên
    $payRates = DB::table('teacher_salary_rules')
        ->select('teacher_id', 'unit', 'pay_rate')
        ->whereIn('teacher_id', $salaries->pluck('teacher_id')->unique())
        ->get();

    $shechedules = DB::table('schedules')
        ->select('id', 'date', 'start_time', 'end_time', 'teacher_id', 'support_teacher', 'day_of_week')
        ->whereIn('teacher_id', $salaries->pluck('class_id')->unique())
        ->get();

    return view('admin.teacher_salaries.index', compact('salaries', 'payRates', 'shechedules'));
}

public function getData(Request $request)
{
    $input = $request->input('month', now()->format('Y-m'));
    [$year, $month] = explode('-', $input);
    $year = (int)$year;
    $month = (int)$month;

    $mainTeacher = DB::table('schedules as s')
        ->where('s.status', 1)
        ->whereMonth('s.date', $month)
        ->whereYear('s.date', $year)
        ->whereNotNull('s.teacher_id')
        ->join('teacher_salary_rules as tsr', function ($j) {
            $j->on('s.teacher_id', '=', 'tsr.teacher_id')
              ->whereRaw('s.date >= tsr.effective_date')
              ->where(function ($q) {
                  $q->whereNull('tsr.end_pay_rate')
                    ->orWhereRaw('s.date <= tsr.end_pay_rate');
              });
        })
        ->leftJoin('users as u', 'u.id', '=', 's.teacher_id')
        ->leftJoin('teacher_salaries as ts', function ($j) use ($month, $year) {
            $j->on('ts.teacher_id', '=', 's.teacher_id')
              ->where('ts.month', $month)
              ->where('ts.year', $year);
        })
        ->select(
            's.teacher_id as teacher_id',
            'u.name as teacher_name',
            'u.phone as teacher_phone',
            DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, s.start_time, s.end_time)/60) as hours'),
            DB::raw('tsr.pay_rate'),
            DB::raw('tsr.unit'),
            'ts.bonus',
            'ts.penalty'
        );

    $supportTeacher = DB::table('schedules as s')
        ->where('s.status', 1)
        ->whereMonth('s.date', $month)
        ->whereYear('s.date', $year)
        ->whereNotNull('s.support_teacher')
        ->join('teacher_salary_rules as tsr', function ($j) {
            $j->on('s.support_teacher', '=', 'tsr.teacher_id')
              ->whereRaw('s.date >= tsr.effective_date')
              ->where(function ($q) {
                  $q->whereNull('tsr.end_pay_rate')
                    ->orWhereRaw('s.date <= tsr.end_pay_rate');
              });
        })
        ->leftJoin('users as u', 'u.id', '=', 's.support_teacher')
        ->leftJoin('teacher_salaries as ts', function ($j) use ($month, $year) {
            $j->on('ts.teacher_id', '=', 's.support_teacher')
              ->where('ts.month', $month)
              ->where('ts.year', $year);
        })
        ->select(
            's.support_teacher as teacher_id',
            'u.name as teacher_name',
            'u.phone as teacher_phone',
            DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, s.start_time, s.end_time)/60) as hours'),
            DB::raw('tsr.pay_rate'),
            DB::raw('tsr.unit'),
            'ts.bonus',
            'ts.penalty'
        );

    // Gộp query
    $combinedQuery = $mainTeacher->unionAll($supportTeacher);

    // Truy vấn từ subquery và group lại theo teacher
    $records = DB::table(DB::raw("({$combinedQuery->toSql()}) as sub"))
        ->mergeBindings($combinedQuery)
        ->select(
            'teacher_id',
            'teacher_name',
            'teacher_phone',
            DB::raw('SUM(hours) as total_hours'),
            DB::raw('MAX(pay_rate) as pay_rate'), // do chỉ có 1 mức lương
            DB::raw('MAX(unit) as unit'),
            DB::raw('MAX(bonus) as bonus'),
            DB::raw('MAX(penalty) as penalty'),
            DB::raw('SUM(hours * pay_rate) as total_salary')
        )
        ->groupBy('teacher_id', 'teacher_name', 'teacher_phone')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $records,
    ]);
}





    public function save(Request $request)
    {
        Log::debug('Saving salaries: ', $request->all()); // <- debug dữ liệu gửi lên


        $month = $request->input('month');
        $year = $request->input('year');
        $salaries = $request->input('salaries', []);

        $checkData = teacher_salaries::where('month', $month)
            ->where('year', $year)
            ->exists();

            
        if ($checkData) {
            return response()->json(['success' => false, 'message' => 'Bảng lương tháng này đã tồn tại.']);
        }

        foreach ($salaries as $salary) {
            teacher_salaries::updateOrCreate(
                [
                    'teacher_id' => $salary['teacher_id'],
                    'month' => $month,
                    'year' => $year,
                ],
                [
                    'pay_rate' => $salary['pay_rate'],
                    'total_hours' => $salary['total_hours'],
                    'bonus' => $salary['bonus'],
                    'penalty' => $salary['penalty'],
                    'total_salary' => (int) str_replace('.', '', $salary['total_salary']),
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    public function updatePayment(Request $request)
    {

        $salary = teacher_salaries::find($request->salary_id);

        if (!$salary) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bản ghi.']);
        }

        if ($request->note) {
            $salary->note = $request->note ?? null;
        }

        if ($request->has('paid')) {

            $salary->paid = $request->paid;
            $salary->payment_date = $request->paid == 1 ? now() : null;
        }

        $salary->save();

        return response()->json(['success' => true]);
    }


public function filter(Request $request)
{
    $query = DB::table('teacher_salaries as ts')
        ->join('users as u', 'u.id', '=', 'ts.teacher_id');

    // Nếu có tháng, lấy khoảng ngày tháng
    if ($request->filled('month')) {
        $date = Carbon::parse($request->month);
        $month = $date->month;
        $year = $date->year;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

        // Lọc theo tháng + năm
        $query->where('ts.month', $month)
              ->where('ts.year', $year);

        // Chỉ lấy pay_rate có hiệu lực trong khoảng tháng đó
        $query->leftJoin(DB::raw("
            (
                SELECT t1.*
                FROM teacher_salary_rules t1
                INNER JOIN (
                    SELECT teacher_id, MAX(effective_date) as effective_date
                    FROM teacher_salary_rules
                    WHERE effective_date <= '{$endOfMonth}'
                      AND (end_pay_rate IS NULL OR end_pay_rate >= '{$startOfMonth}')
                    GROUP BY teacher_id
                ) t2 ON t1.teacher_id = t2.teacher_id AND t1.effective_date = t2.effective_date
            ) as tsr
        "), 'ts.teacher_id', '=', 'tsr.teacher_id');
    } else {
        // Nếu không chọn tháng, chỉ lấy pay_rate đang có hiệu lực hôm nay
        $today = Carbon::now()->toDateString();

        $query->leftJoin(DB::raw("
            (
                SELECT t1.*
                FROM teacher_salary_rules t1
                INNER JOIN (
                    SELECT teacher_id, MAX(effective_date) as effective_date
                    FROM teacher_salary_rules
                    WHERE effective_date <= '{$today}'
                      AND (end_pay_rate IS NULL OR end_pay_rate >= '{$today}')
                    GROUP BY teacher_id
                ) t2 ON t1.teacher_id = t2.teacher_id AND t1.effective_date = t2.effective_date
            ) as tsr
        "), 'ts.teacher_id', '=', 'tsr.teacher_id');
    }

    // Lọc theo tên
    if ($request->filled('name')) {
        $query->where('u.name', 'like', '%' . $request->name . '%');
    }

    // Lọc trạng thái trả lương
   if ($request->filled('status') && $request->status !== 'all') {
    $query->where('ts.paid', (int)$request->status);
    } else {
        // Nếu không chọn, lọc cả trạng thái 0 và 1 (tránh null)
        $query->whereIn('ts.paid', [0, 1]);
    }

    // Select cuối
    $salaries = $query->select(
        'ts.*',
        'u.name as teacher_name',
        'u.phone as teacher_phone',
        'tsr.pay_rate'
    )->get();

    return response()->json(['success' => true, 'data' => $salaries]);
}

}
