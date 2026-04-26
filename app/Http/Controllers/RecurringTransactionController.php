<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Services\RecurringTransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecurringTransactionController extends Controller
{
    public function __construct(
        protected RecurringTransactionService $recurringTransactionService
    ) {
    }

    public function index()
    {
        $recurringTransactions = RecurringTransaction::query()
            ->where('user_id', auth()->id())
            ->with(['account', 'category'])
            ->orderByDesc('is_active')
            ->orderBy('next_due_date')
            ->get();

        $stats = [
            'active' => $recurringTransactions->where('is_active', true)->count(),
            'due' => $recurringTransactions->filter->isDue()->count(),
            'upcoming' => $recurringTransactions->filter->isDueSoon()->count(),
        ];

        return view('recurring-transactions.index', compact('recurringTransactions', 'stats'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        $categories = Category::where('user_id', auth()->id())->get();

        return view('recurring-transactions.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);

        $this->recurringTransactionService->create($request->user(), $validated);

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Recurring item created successfully.');
    }

    public function show(RecurringTransaction $recurringTransaction)
    {
        abort_if($recurringTransaction->user_id !== auth()->id(), 403);

        return redirect()->route('recurring-transactions.edit', $recurringTransaction);
    }

    public function edit(RecurringTransaction $recurringTransaction)
    {
        abort_if($recurringTransaction->user_id !== auth()->id(), 403);

        $accounts = Account::where('user_id', auth()->id())->get();
        $categories = Category::where('user_id', auth()->id())->get();

        return view('recurring-transactions.edit', compact('recurringTransaction', 'accounts', 'categories'));
    }

    public function update(Request $request, RecurringTransaction $recurringTransaction)
    {
        abort_if($recurringTransaction->user_id !== auth()->id(), 403);

        $validated = $this->validateInput($request);

        $this->recurringTransactionService->update($recurringTransaction, $validated);

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Recurring item updated successfully.');
    }

    public function destroy(RecurringTransaction $recurringTransaction)
    {
        abort_if($recurringTransaction->user_id !== auth()->id(), 403);

        $recurringTransaction->delete();

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Recurring item deleted successfully.');
    }

    public function confirm(RecurringTransaction $recurringTransaction)
    {
        abort_if($recurringTransaction->user_id !== auth()->id(), 403);

        try {
            $this->recurringTransactionService->confirm($recurringTransaction);
        } catch (InsufficientBalanceException $exception) {
            return back()->withErrors(['recurring_transaction' => $exception->getMessage()]);
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Recurring transaction confirmed and added to your log.');
    }

    protected function validateInput(Request $request): array
    {
        return $request->validate([
            'account_id' => 'required|integer',
            'category_id' => 'required|integer',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'frequency' => 'required|in:weekly,monthly',
            'interval' => 'required|integer|min:1|max:12',
            'reminder_days_before' => 'required|integer|min:0|max:14',
            'start_date' => 'required|date',
            'next_due_date' => 'required|date|after_or_equal:start_date',
            'ends_on' => 'nullable|date|after_or_equal:next_due_date',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
