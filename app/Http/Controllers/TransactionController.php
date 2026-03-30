<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
                        ->with(['account', 'category'])
                        ->latest()
                        ->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts   = Account::where('user_id', auth()->id())->get();
        $categories = Category::where('user_id', auth()->id())->get();
        return view('transactions.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id'       => 'required|exists:accounts,id',
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create([
            'user_id'          => auth()->id(),
            'account_id'       => $request->account_id,
            'category_id'      => $request->category_id,
            'type'             => $request->type,
            'amount'           => $request->amount,
            'description'      => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        $account = Account::find($request->account_id);
        if ($request->type === 'income') {
            $account->balance += $request->amount;
        } else {
            $account->balance -= $request->amount;
        }
        $account->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully!');
    }

    public function edit(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        $accounts   = Account::where('user_id', auth()->id())->get();
        $categories = Category::where('user_id', auth()->id())->get();
        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        $request->validate([
            'account_id'       => 'required|exists:accounts,id',
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        // Reverse old balance
        $oldAccount = Account::find($transaction->account_id);
        if ($transaction->type === 'income') {
            $oldAccount->balance -= $transaction->amount;
        } else {
            $oldAccount->balance += $transaction->amount;
        }
        $oldAccount->save();

        $transaction->update($request->only('account_id', 'category_id', 'type', 'amount', 'description', 'transaction_date'));

        // Apply new balance
        $newAccount = Account::find($request->account_id);
        if ($request->type === 'income') {
            $newAccount->balance += $request->amount;
        } else {
            $newAccount->balance -= $request->amount;
        }
        $newAccount->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        $account = Account::find($transaction->account_id);
        if ($transaction->type === 'income') {
            $account->balance -= $transaction->amount;
        } else {
            $account->balance += $transaction->amount;
        }
        $account->save();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }
}