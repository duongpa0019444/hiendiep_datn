<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\orders;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    //
    public function dashBoard(HttpRequest $request)
    {
        return view('admin.dashdoard');
    }


}
