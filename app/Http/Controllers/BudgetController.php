<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Category;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $categories = Category::where('user_id', auth()->id())->get();
        $budgets = Budget::where('user_id', auth()->id())->where('year', $year)->where('month', $month)->get()->keyBy('category_id');

        return view('budgets.index', compact('categories','budgets','year','month'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'amounts' => 'array',
        ]);

        $year = $request->year;
        $month = $request->month;

        $amounts = $request->amounts ?? [];
        foreach ($amounts as $categoryId => $amt) {
            $amt = floatval($amt) ?: 0.0;
            Budget::updateOrCreate(
                ['user_id' => auth()->id(), 'category_id' => $categoryId, 'year' => $year, 'month' => $month],
                ['amount' => $amt]
            );
        }

        return redirect()->route('budgets.index', ['year'=>$year,'month'=>$month])->with('success','Budgets saved.');
    }
}
