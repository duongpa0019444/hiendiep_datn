<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ActionLogController extends Controller
{

    public function index(Request $request)
    {

        $logs = DB::table('action_logs')
            ->leftJoin('users', 'action_logs.user_id', '=', 'users.id')
            ->select(
                'action_logs.*',
                'users.name as user_name'
            )
            ->orderBy('action_logs.created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'logs' => $logs,
                'pagination' => $logs->links('pagination::bootstrap-5')->toHtml()
            ]);
        }
        return view('admin.actions_log.actions_log-list', compact('logs'));
    }


    public function filter(Request $request)
    {
        $logs = $this->getFilterActionLog($request);

        // Giữ lại tham số lọc khi phân trang
        $logs->appends($request->all());

        return response()->json([
            'logs' => $logs,
            'pagination' => $logs->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterActionLog(Request $request)
    {
        $query = DB::table('action_logs')
            ->leftJoin('users', 'action_logs.user_id', '=', 'users.id')
            ->select(
                'action_logs.*',
                'users.name as user_name'
            )
            ->orderBy('action_logs.created_at', 'desc');

        // Lọc theo model_type (tên bảng)
        if ($request->filled('model_type')) {
            $query->where('action_logs.model_type', $request->model_type);
        }

        // Lọc theo model_type (tên bảng)
        if ($request->filled('action')) {
            $query->where('action_logs.action', $request->action);
        }

        // Lọc theo từ khóa (user_name hoặc description)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('users.name', 'like', "%{$keyword}%")
                    ->orWhere('action_logs.description', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo ngày tạo
        if ($request->filled('date')) {
            $query->whereDate('action_logs.created_at', $request->date);
        }

        return $query->paginate($request->limit ?? 10);
    }



    public function delete(Request $request, $id = null)
    {
        // Xóa log đơn
        if ($id) {
            $log = DB::table('action_logs')->where('id', $id)->first();
            if (!$log) {
                return response()->json(['error' => 'Log không tồn tại.'], 404);
            }

            DB::table('action_logs')->where('id', $id)->delete();
            return response()->json(['success' => 'Xóa log thành công.']);
        }

        // Xóa nhiều log
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['error' => 'Vui lòng chọn ít nhất một log để xóa.'], 400);
        }

        DB::table('action_logs')->whereIn('id', $ids)->delete();
        return response()->json(['success' => 'Xóa thành công các log đã chọn.']);
    }

    //Chi tiết log
    public function viewLog($id)
    {
        try {
            $log = DB::table('action_logs')
                ->leftJoin('users', 'action_logs.user_id', '=', 'users.id')
                ->select(
                    'action_logs.*',
                    'users.name as name'
                )
                ->where('action_logs.id', $id)
                ->first();

            if (!$log) {
                return response()->json(['error' => 'Log không tồn tại.'], 404);
            }

            // Phân tích User-Agent
            $userAgentInfo = $this->parseUserAgent($log->user_agent);

            $ipData = Http::get('https://ipwho.is/'.$log->ip_address)->json();

            return response()->json([
                'log' => $log,
                'user_agent_info' => $userAgentInfo,
                'ip_info' => [
                    'ip'            => $ipData['ip'],
                    'country'       => $ipData['country'] ?? 'không xác định',
                    'country_code'  => $ipData['country_code'] ?? 'không xác định',
                    'continent'     => $ipData['continent'] ?? 'không xác định',
                    'region'        => $ipData['region'] ?? 'không xác định',
                    'city'          => $ipData['city']  ?? 'không xác định',
                    'latitude'      => $ipData['latitude']  ?? null,
                    'longitude'     => $ipData['longitude'] ?? null,
                    'timezone'      => $ipData['timezone']['id'] ?? $ipData['timezone'] ?? null,
                    'currency'      => $ipData['currency']['code'] ?? null,
                    'isp'           => $ipData['connection']['isp'] ?? null,
                    'org'           => $ipData['connection']['org'] ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ", file: " . $e->getFile() . ", line: " . $e->getLine());
            return response()->json(['error' => 'Lỗi khi lấy thông tin log: ' . $e->getMessage()], 500);
        }
    }

    function parseUserAgent($userAgent)
    {
        $result = [
            'os' => 'Không xác định',
            'browser' => 'Không xác định',
            'browser_version' => 'Không rõ',
            'engine' => 'Không xác định'
        ];

        // Phân tích hệ điều hành
        if (preg_match('/Windows NT 10\.0/', $userAgent)) {
            $result['os'] = 'Windows 10';
        } elseif (preg_match('/Windows NT 6\.3/', $userAgent)) {
            $result['os'] = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6\.2/', $userAgent)) {
            $result['os'] = 'Windows 8';
        } elseif (preg_match('/Windows NT 6\.1/', $userAgent)) {
            $result['os'] = 'Windows 7';
        } elseif (preg_match('/Mac OS X/', $userAgent)) {
            $result['os'] = 'Mac OS X';
        } elseif (preg_match('/Linux/', $userAgent)) {
            $result['os'] = 'Linux';
        }

        // Phân tích trình duyệt & phiên bản
        if (preg_match('/Edg\/([\d\.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Microsoft Edge';
            $result['browser_version'] = $matches[1];
        } elseif (preg_match('/Chrome\/([\d\.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Google Chrome';
            $result['browser_version'] = $matches[1];
        } elseif (preg_match('/Firefox\/([\d\.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Mozilla Firefox';
            $result['browser_version'] = $matches[1];
        } elseif (preg_match('/Safari\/([\d\.]+)/', $userAgent, $matches) && !strpos($userAgent, 'Chrome')) {
            $result['browser'] = 'Apple Safari';
            $result['browser_version'] = $matches[1];
        }

        // Phân tích engine
        if (strpos($userAgent, 'AppleWebKit') !== false) {
            $result['engine'] = 'WebKit';
        }
        if (strpos($userAgent, 'Gecko') !== false && strpos($userAgent, 'like Gecko') === false) {
            $result['engine'] = 'Gecko';
        }

        return $result;
    }
}
