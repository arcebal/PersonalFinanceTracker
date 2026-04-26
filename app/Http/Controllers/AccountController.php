<?php
namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())->latest()->get();
        $categories = \App\Models\Category::where('user_id', auth()->id())->get();
        return view('accounts.index', compact('accounts', 'categories'));
    }

    public function trashed()
    {
        $accounts = Account::onlyTrashed()->where('user_id', auth()->id())->get();
        return view('accounts.trashed', compact('accounts'));
    }

    public function restore($id)
    {
        $a = Account::withTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $a->restore();
        return redirect()->back()->with('success', 'Account restored.');
    }

    public function forceDelete($id)
    {
        $a = Account::withTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $a->forceDelete();
        return redirect()->back()->with('success', 'Account permanently deleted.');
    }

    public function exportCsv()
    {
        $accounts = Account::where('user_id', auth()->id())->latest()->get();

        $filename = 'accounts_export_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($accounts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name','Type','Balance','Description']);
            foreach ($accounts as $a) {
                fputcsv($handle, [
                    $a->name,
                    $a->type,
                    $a->balance,
                    $a->description,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:cash,e-wallet',
            'balance'     => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        Account::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'type'        => $request->type,
            'balance'     => $request->balance,
            'description' => $request->description,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully!');
    }

    public function show(Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);
        // redirect to edit page for simplicity
        return redirect()->route('accounts.edit', $account);
    }

    public function edit(Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:cash,e-wallet',
            'balance'     => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);
        $account->update($request->only('name', 'type', 'balance', 'description'));
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully!');
    }

    public function destroy(Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);
        $account->delete();
        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully!')
            ->with('undo', route('accounts.restore', $account->id));
    }
}