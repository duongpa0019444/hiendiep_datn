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

        $salaries = $data = DB::table('teacher_salaries as ts')
            ->join('users as u', 'u.id', '=', 'ts.teacher_id')
            ->leftJoin('teacher_salary_rules as tsr', function ($join) {
                $join->on('ts.teacher_id', '=', 'tsr.teacher_id')
                    ->where('tsr.effective_date', '<=', Carbon::now());
            })
            ->select(
                'ts.*',
                'u.name as teacher_name',
                'u.phone as teacher_phone',
                'tsr.pay_rate',
            )
            ->where('ts.month', $month)
            ->where('ts.year', $year)
            ->get();

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
        $inputMonth = $request->input('month'); // Ví dụ: '2025-06'

        if ($inputMonth) {
            [$year, $month] = explode('-', $inputMonth);
        } else {
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
        }

        // Main teacher
        $mainTeachers = DB::table('schedules as s')
            ->join('teacher_salary_rules as tsr', function ($join) {
                $join->on('s.teacher_id', '=', 'tsr.teacher_id')
                    ->whereRaw('s.date >= tsr.effective_date')
                    ->where(function ($query) {
                        $query->whereNull('tsr.end_pay_rate')
                            ->orWhereRaw('s.date <= tsr.end_pay_rate');
                    });
            })
            ->whereNotNull('s.teacher_id')
            ->whereMonth('s.date', $month)
            ->whereYear('s.date', $year)
            ->select(
                's.teacher_id',
                DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, s.start_time, s.end_time)/60) as teaching_hours'),
                'tsr.pay_rate',
                'tsr.unit',
                's.date'
            );

        // Support teacher
        $supportTeachers = DB::table('schedules as s')
            ->join('teacher_salary_rules as tsr', function ($join) {
                $join->on('s.support_teacher', '=', 'tsr.teacher_id')
                    ->whereRaw('s.date >= tsr.effective_date')
                    ->where(function ($query) {
                        $query->whereNull('tsr.end_pay_rate')
                            ->orWhereRaw('s.date <= tsr.end_pay_rate');
                    });
            })
            ->whereNotNull('s.support_teacher')
            ->whereMonth('s.date', $month)
            ->whereYear('s.date', $year)
            ->select(
                DB::raw('s.support_teacher as teacher_id'),
                DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, s.start_time, s.end_time)/60) as teaching_hours'),
                'tsr.pay_rate',
                'tsr.unit',
                's.date'
            );

        // Gộp lại
        $teaching = DB::table(DB::raw("({$mainTeachers->toSql()} UNION ALL {$supportTeachers->toSql()}) as teaching"))
            ->mergeBindings($mainTeachers)
            ->mergeBindings($supportTeachers);

        // Tính tổng lương cho mỗi giáo viên
        $teachingDatas = DB::table(DB::raw("({$teaching->toSql()}) as teaching_data"))
            ->mergeBindings($teaching)
            ->join('users as u', 'u.id', '=', 'teaching_data.teacher_id')
            ->leftJoin('teacher_salaries as ts', function ($join) use ($month, $year) {
                $join->on('ts.teacher_id', '=', 'teaching_data.teacher_id')
                    ->where('ts.month', '=', $month)
                    ->where('ts.year', '=', $year);
            })
            ->select(
                'teaching_data.teacher_id',
                'u.name as teacher_name',
                'u.phone as teacher_phone',
                DB::raw('SUM(teaching_data.teaching_hours) as total_hours'),
                DB::raw('SUM(teaching_data.teaching_hours * teaching_data.pay_rate) as total_salary'),
                DB::raw('MAX(teaching_data.pay_rate) as pay_rate'),
                DB::raw('MAX(teaching_data.unit) as unit'),
                'ts.bonus',
                'ts.penalty'
            )
            ->groupBy(
                'teaching_data.teacher_id',
                'u.name',
                'u.phone',
                'ts.bonus',
                'ts.penalty'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $teachingDatas,
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
            ->join('users as u', 'u.id', '=', 'ts.teacher_id')
            ->leftJoin('teacher_salary_rules as tsr', function ($join) {
                $join->on('ts.teacher_id', '=', 'tsr.teacher_id');
            })
            ->select(
                'ts.*',
                'u.name as teacher_name',
                'u.phone as teacher_phone',
                'tsr.pay_rate'
            );

        if ($request->filled('name')) {
            $query->where('u.name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $query->where('ts.month', $date->month)
                ->where('ts.year', $date->year);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('ts.paid', $request->status);
        }


        $salaries = $query->get();

        return response()->json(['success' => true, 'data' => $salaries]);
    }
}
