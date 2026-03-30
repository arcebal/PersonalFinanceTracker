<?php
namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())->latest()->get();
        return view('accounts.index', compact('accounts'));
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
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully!');
    }
}