<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ThongKeController extends Controller
{
    public function index()
    {
        return view('admin.thongkedaotao');
    }
    public function studyStatistics()
    {
        return view('admin.thongketaichinh');
    }
}
