<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
class SetLocale
{
 // app/Http/Middleware/SetLocale.php
public function handle($request, Closure $next)
{
    $locale = session('locale', config('app.locale')); // Lấy locale từ session hoặc giá trị mặc định
    app()->setLocale($locale); // Thiết lập locale cho ứng dụng

    return $next($request); // Tiếp tục xử lý request
}


}
