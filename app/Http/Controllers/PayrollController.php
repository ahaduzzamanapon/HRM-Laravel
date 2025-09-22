<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        return view('payroll.index');
    }
}
