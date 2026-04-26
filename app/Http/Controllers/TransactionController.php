<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', auth()->id())->with(['account','category']);

        // Filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by description or amount
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qwhere) use ($q) {
                $qwhere->where('description', 'like', "%{$q}%")->orWhere('amount', $q);
            });
        }

        $transactions = $query->latest()->get();

        $categories = \App\Models\Category::where('user_id', auth()->id())->get();
        $accounts = \App\Models\Account::where('user_id', auth()->id())->get();

        $filters = $request->only(['start_date','end_date','category_id','account_id','type','q']);

        return view('transactions.index', compact('transactions','categories','accounts','filters'));
    }

    public function trashed()
    {
        $transactions = Transaction::onlyTrashed()->where('user_id', auth()->id())->with(['account','category'])->get();
        return view('transactions.trashed', compact('transactions'));
    }

    public function restore($id)
    {
        $t = Transaction::withTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $t->restore();
        // Reapply balance
        $account = Account::find($t->account_id);
        if ($t->type === 'income') {
            $account->balance += $t->amount;
        } else {
            $account->balance -= $t->amount;
        }
        $account->save();
        return redirect()->back()->with('success', 'Transaction restored.');
    }

    public function forceDelete($id)
    {
        $t = Transaction::withTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $t->forceDelete();
        return redirect()->back()->with('success', 'Transaction permanently deleted.');
    }

    public function exportCsv()
    {
        $transactions = Transaction::where('user_id', auth()->id())->with(['account','category'])->latest()->get();

        $filename = 'transactions_export_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date','Description','Category','Account','Type','Amount']);
            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->transaction_date,
                    $t->description,
                    $t->category->name ?? '',
                    $t->account->name ?? '',
                    $t->type,
                    $t->amount,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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

        $account = Account::find($request->account_id);
        // Prevent expense that exceeds account balance
        if ($request->type === 'expense' && $account && $account->balance < $request->amount) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Insufficient account balance'], 422);
            }
            return back()->withErrors(['amount' => 'Insufficient account balance'])->withInput();
        }

        Transaction::create([
            'user_id'          => auth()->id(),
            'account_id'       => $request->account_id,
            'category_id'      => $request->category_id,
            'type'             => $request->type,
            'amount'           => $request->amount,
            'description'      => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        if ($request->type === 'income') {
            $account->balance += $request->amount;
        } else {
            $account->balance -= $request->amount;
        }
        $account->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully!');
    }

    public function show(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        return redirect()->route('transactions.edit', $transaction);
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

        // provide undo link (restore) in session
        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully!')
            ->with('undo', route('transactions.restore', $transaction->id));
    }
}