<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalance = Account::where('user_id', auth()->id())->sum('balance');
        $totalIncome  = Transaction::where('user_id', auth()->id())->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', auth()->id())->where('type', 'expense')->sum('amount');
        $recentTrans  = Transaction::where('user_id', auth()->id())
                            ->with(['category', 'account'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('dashboard', compact('totalBalance', 'totalIncome', 'totalExpense', 'recentTrans'));
    }
}