<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\staff_salaries;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffSalaryController extends Controller
{
    public function index()
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            
            return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn không có quyền quản trị này.');
            
        }

        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString(); // YYYY-MM-01
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();     // YYYY-MM-31

        $salaries = DB::table('staff_salaries as ss')
            ->join('users as u', 'u.id', '=', 'ss.staff_id')
            ->leftJoin('staff_salary_rules as ssr', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('ss.staff_id', '=', 'ssr.staff_id')
                    ->where('ssr.start_pay_rate', '<=', $endOfMonth)
                    ->where(function ($q) use ($startOfMonth) {
                        $q->whereNull('ssr.end_pay_rate')
                            ->orWhere('ssr.end_pay_rate', '>=', $startOfMonth);
                    });
            })
            ->select(
                'ss.*',
                'u.name as staff_name',
                'u.phone as staff_phone',
                'ssr.base_salary',
                'ssr.salary_coefficient',
                'ssr.insurance',
            )
            ->where('ss.month', $month)
            ->where('ss.year', $year)
            ->paginate(3);

        // Các phần còn lại giữ nguyên
        // $payRates = DB::table('staff_salary_rules')
        //     ->select('staff_id', 'unit', 'pay_rate')
        //     ->whereIn('staff_id', $salaries->pluck('staff_id')->unique())
        //     ->get();

        // $shechedules = DB::table('schedules')
        //     ->select('id', 'date', 'start_time', 'end_time', 'staff_id', 'support_staff', 'day_of_week')
        //     ->whereIn('staff_id', $salaries->pluck('class_id')->unique())
        //     ->get();
        $salaryStatus = DB::table('staff_salaries')
        ->where('month', $month)
        ->where('year', $year)
        ->first();

         $isLocked = $salaryStatus ? ($salaryStatus->active == 1) : false;
        return view('admin.staff_salaries.index', compact('salaries', 'isLocked'));
    }

 public function getData(Request $request)
{   
    // Kiểm tra quyền của người dùng hiện tại
    if (Auth::user()->role == 'staff') {
        return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền quản trị này.');
    }

    // Lấy month từ request (dạng YYYY-MM) hoặc mặc định tháng hiện tại
    $selectedMonth = $request->input('month', now()->format('Y-m'));
    $targetDate = $selectedMonth . '-01'; // luôn lấy ngày đầu tháng

    $data = DB::table('staff_salary_rules as r')
        ->join('users as u', 'u.id', '=', 'r.staff_id')
        ->select(
            'r.staff_id',
            'u.name',
            'u.phone',
            'r.base_salary',
            'r.salary_coefficient',
            'r.start_pay_rate',
            'r.end_pay_rate',
            'r.insurance',
            DB::raw('(r.base_salary * r.salary_coefficient) - (r.insurance * (r.base_salary * r.salary_coefficient)/100) as total_salary')
        )
        ->where('r.start_pay_rate', '<=', $targetDate)
        ->where(function ($query) use ($targetDate) {
            $query->whereNull('r.end_pay_rate')
                  ->orWhere('r.end_pay_rate', '>=', $targetDate);
        })
        ->get();

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}


    public function save(Request $request)
    {   
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            
            return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn không có quyền quản trị này.');
            
        }
        Log::debug('Saving salaries: ', $request->all()); // <- debug dữ liệu gửi lên


        $month = $request->input('month');
        $year = $request->input('year');
        $salaries = $request->input('salaries', []);

        $checkData = staff_salaries::where('month', $month)
            ->where('year', $year)
            ->exists();


        if ($checkData) {
            return response()->json(['success' => false, 'message' => 'Bảng lương tháng này đã tồn tại.']);
        }

        foreach ($salaries as $salary) {
            staff_salaries::updateOrCreate(
                [
                    'staff_id' => $salary['staff_id'],
                    'month' => $month,
                    'year' => $year,
                ],
                [
                    'insurance_fee' => $salary['insurance'],
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
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            
            return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn không có quyền quản trị này.');
            
        }
        $salary = staff_salaries::find($request->salary_id);

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

       return response()->json([
                    'success' => true,
                    'paid' => $salary->paid,
                    'payment_date' => $salary->payment_date, // dạng YYYY-MM-DD
                    ]);
    }


    public function filter(Request $request)
    {   
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            
            return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn không có quyền quản trị này.');
            
        }
        $query = DB::table('staff_salaries as ts')
            ->join('users as u', 'u.id', '=', 'ts.staff_id');
        $month = null;
        $year = null;
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
                FROM staff_salary_rules t1
                INNER JOIN (
                    SELECT staff_id, MAX(start_pay_rate) as start_pay_rate
                    FROM staff_salary_rules
                    WHERE start_pay_rate <= '{$endOfMonth}'
                      AND (end_pay_rate IS NULL OR end_pay_rate >= '{$startOfMonth}')
                    GROUP BY staff_id
                ) t2 ON t1.staff_id = t2.staff_id AND t1.start_pay_rate = t2.start_pay_rate
            ) as tsr
        "), 'ts.staff_id', '=', 'tsr.staff_id');
        } else {
            // Nếu không chọn tháng, chỉ lấy pay_rate đang có hiệu lực hôm nay
            $today = Carbon::now()->toDateString();

            $query->leftJoin(DB::raw("
            (
                SELECT t1.*
                FROM staff_salary_rules t1
                INNER JOIN (
                    SELECT staff_id, MAX(start_pay_rate) as start_pay_rate
                    FROM staff_salary_rules
                    WHERE start_pay_rate <= '{$today}'
                      AND (end_pay_rate IS NULL OR end_pay_rate >= '{$today}')
                    GROUP BY staff_id
                ) t2 ON t1.staff_id = t2.staff_id AND t1.start_pay_rate = t2.start_pay_rate
            ) as tsr
        "), 'ts.staff_id', '=', 'tsr.staff_id');
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
            'u.name as staff_name',
            'u.phone as staff_phone',
            'tsr.base_salary',
            'tsr.salary_coefficient',
            'tsr.start_pay_rate',
            'tsr.end_pay_rate',
            'tsr.insurance',
            DB::raw('(tsr.base_salary * tsr.salary_coefficient) - (tsr.insurance * (tsr.base_salary * tsr.salary_coefficient)/100) as total_salary')
        )->get();

        $isLocked = null;
        if ($month && $year) {
            $isLocked = DB::table('staff_salaries')
                ->where('month', $month)
                ->where('year', $year)
                ->value('active'); // 1 = chốt, 0 = mở
        }

        return response()->json(['success' => true, 'data' => $salaries, 'isLocked' => $isLocked ?? 0]);
    }

    public function lock(Request $request)
        {
            $request->validate([
                'month' => 'required|integer|min:1|max:12',
                'year'  => 'required|integer|min:2000'
            ]);
               $month = (int)$request->month;
                 $year  = (int)$request->year;
            // Kiểm tra nếu chưa có bảng lương của tháng/năm này
            $hasSalary = DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();

            if (!$hasSalary) {
                return response()->json([
                    'success' => false,
                    'message' => "Không tìm thấy bảng lương tháng {$request->month}/{$request->year}."
                ]);
            }

            // Kiểm tra nếu đã chốt trước đó
            $isLocked = DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->where('active', 1)
                ->exists();

            if ($isLocked) {
                return response()->json([
                    'success' => false,
                    'message' => "Bảng lương tháng {$request->month}/{$request->year} đã được chốt trước đó."
                ]);
            }

            // 🔹 Kiểm tra nếu có bản ghi chưa thanh toán (payment_status != 'paid')
            $hasUnpaid = DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->where('paid',  0)
                ->exists();

            if ($hasUnpaid) {
                return response()->json([
                    'success' => false,
                    'message' => "Không thể chốt vì vẫn còn giáo viên chưa được thanh toán lương tháng {$request->month}/{$request->year}."
                ]);
            }

            // Chốt bảng lương
            DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->update(['active' => 1]);
            
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString(); // YYYY-MM-01
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();     // YYYY-MM-31

            $salaries = DB::table('staff_salaries as ss')
                ->join('users as u', 'u.id', '=', 'ss.staff_id')
                ->leftJoin('staff_salary_rules as ssr', function ($join) use ($startOfMonth, $endOfMonth) {
                    $join->on('ss.staff_id', '=', 'ssr.staff_id')
                        ->where('ssr.start_pay_rate', '<=', $endOfMonth)
                        ->where(function ($q) use ($startOfMonth) {
                            $q->whereNull('ssr.end_pay_rate')
                                ->orWhere('ssr.end_pay_rate', '>=', $startOfMonth);
                        });
                })
                ->select(
                    'ss.*',
                    'u.name as staff_name',
                    'u.phone as staff_phone',
                    'ssr.base_salary',
                    'ssr.salary_coefficient',
                    'ssr.insurance',
                )
                ->where('ss.month', $month)
                ->where('ss.year', $year)
                ->get();

            return response()->json([
                'success' => true,
                'message' => "Đã chốt bảng lương tháng {$request->month}/{$request->year}",
                'data' => $salaries
            ]);
        }
    public function unlock(Request $request)
        {
            $request->validate([
                'month' => 'required|integer|min:1|max:12',
                'year'  => 'required|integer|min:2000'
            ]);
                $month = (int)$request->month;
                 $year  = (int)$request->year;
            // Kiểm tra nếu chưa có bảng lương của tháng/năm này
            $hasSalary = DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();

            if (!$hasSalary) {
                return response()->json([
                    'success' => false,
                    'message' => "Không tìm thấy bảng lương tháng {$request->month}/{$request->year}."
                ]);
            }

            // Kiểm tra nếu bảng lương chưa chốt (active = 0)
            $isLocked = DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->where('active', 1)
                ->exists();

            if (!$isLocked) {
                return response()->json([
                    'success' => false,
                    'message' => "Bảng lương tháng {$request->month}/{$request->year} chưa được chốt, không thể mở khóa."
                ]);
            }

            // Mở khóa bảng lương
            DB::table('staff_salaries')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->update(['active' => 0]);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->toDateString(); // YYYY-MM-01
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();     // YYYY-MM-31

        $salaries = DB::table('staff_salaries as ss')
            ->join('users as u', 'u.id', '=', 'ss.staff_id')
            ->leftJoin('staff_salary_rules as ssr', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('ss.staff_id', '=', 'ssr.staff_id')
                    ->where('ssr.start_pay_rate', '<=', $endOfMonth)
                    ->where(function ($q) use ($startOfMonth) {
                        $q->whereNull('ssr.end_pay_rate')
                            ->orWhere('ssr.end_pay_rate', '>=', $startOfMonth);
                    });
            })
            ->select(
                'ss.*',
                'u.name as staff_name',
                'u.phone as staff_phone',
                'ssr.base_salary',
                'ssr.salary_coefficient',
                'ssr.insurance',
            )
            ->where('ss.month', $month)
            ->where('ss.year', $year)
            ->get();

            return response()->json([
                'success' => true,
                'message' => "Đã mở khóa bảng lương tháng {$request->month}/{$request->year}.",
                'data' => $salaries
            ]);
        }

}
