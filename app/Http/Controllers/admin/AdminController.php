<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use App\Models\CashOut;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalCashIn = Cash::sum('amount');

        $totalCashOut = CashOut::sum('amount');

        $totalBalance = $totalCashIn - $totalCashOut;

        return view('admin.dashboard', compact('totalCashIn', 'totalCashOut', 'totalBalance'));
    }
}
