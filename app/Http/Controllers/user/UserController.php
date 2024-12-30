<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use App\Models\CashOut;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $totalCashIn = Cash::sum('amount');

        $totalCashOut = CashOut::sum('amount');

        $totalBalance = $totalCashIn - $totalCashOut;

        return view('user.dashboard', compact('totalCashIn', 'totalCashOut', 'totalBalance'));
    }
}
