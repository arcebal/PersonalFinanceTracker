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

        // Pie chart data (expense breakdown by category)
        $pieLabels = [];
        $pieData = [];
        $pieColors = [];
        $categories = \App\Models\Category::where('user_id', auth()->id())->get();
        foreach ($categories as $cat) {
            $sum = Transaction::where('user_id', auth()->id())->where('category_id', $cat->id)->where('type', 'expense')->sum('amount');
            if ($sum > 0) {
                $pieLabels[] = $cat->name;
                $pieData[] = (float) $sum;
                $pieColors[] = $cat->color;
            }
        }

        // Monthly income/expense for last 6 months
        $months = [];
        $income = [];
        $expense = [];
        $now = \Carbon\Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $months[] = $m->format('M');
            $start = $m->copy()->startOfMonth()->toDateString();
            $end = $m->copy()->endOfMonth()->toDateString();
            $income[] = (float) Transaction::where('user_id', auth()->id())->where('type', 'income')->whereBetween('transaction_date', [$start, $end])->sum('amount');
            $expense[] = (float) Transaction::where('user_id', auth()->id())->where('type', 'expense')->whereBetween('transaction_date', [$start, $end])->sum('amount');
        }

        // Budget vs actual for current month
        $current = \Carbon\Carbon::now();
        $bYear = $current->year;
        $bMonth = $current->month;
        $budgets = \App\Models\Budget::where('user_id', auth()->id())->where('year', $bYear)->where('month', $bMonth)->with('category')->get();
        $budgetLabels = [];
        $budgetAmounts = [];
        $spentAmounts = [];
        foreach ($budgets as $b) {
            $budgetLabels[] = $b->category->name;
            $budgetAmounts[] = (float) $b->amount;
            $spent = Transaction::where('user_id', auth()->id())->where('category_id', $b->category_id)->where('type', 'expense')->whereYear('transaction_date', $bYear)->whereMonth('transaction_date', $bMonth)->sum('amount');
            $spentAmounts[] = (float) $spent;
        }

        return view('dashboard', compact('totalBalance', 'totalIncome', 'totalExpense', 'recentTrans', 'pieLabels', 'pieData', 'pieColors', 'months', 'income', 'expense', 'budgetLabels', 'budgetAmounts', 'spentAmounts'));
    }
}