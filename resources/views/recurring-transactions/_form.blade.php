@php
    $item = $recurringTransaction ?? null;
    $isEditing = $item !== null;
@endphp

<form method="POST" action="{{ $action }}" class="auth-form">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div class="panel-heading mb-6">
        <div class="panel-title-block">
            <span class="page-kicker">{{ $isEditing ? 'Edit plan' : 'New plan' }}</span>
            <h2 class="text-2xl font-extrabold text-[var(--text-primary)]">Recurring transaction details</h2>
            <p class="panel-subtitle">Set a repeating income or expense, choose when it becomes due, and decide how early the reminder should appear.</p>
        </div>
    </div>

    <div class="form-grid">
        <div class="form-field md:col-span-6">
            <label class="field-label">Label</label>
            <input type="text" name="description" value="{{ old('description', $item?->description) }}" placeholder="Rent, Salary, Netflix, Utilities" required>
            @error('description')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label">Type</label>
            @php($selectedType = old('type', $item?->type ?? 'expense'))
            <select name="type" id="tx-type">
                <option value="income" {{ $selectedType === 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $selectedType === 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
            @error('type')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label">Account</label>
            <select name="account_id">
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" {{ (string) old('account_id', $item?->account_id) === (string) $account->id ? 'selected' : '' }}>
                        {{ $account->name }} (P{{ number_format($account->balance, 2) }})
                    </option>
                @endforeach
            </select>
            @error('account_id')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label">Category</label>
            <select name="category_id" id="tx-category">
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        data-type="{{ $category->type }}"
                        {{ (string) old('category_id', $item?->category_id) === (string) $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }} ({{ ucfirst($category->type) }})
                    </option>
                @endforeach
            </select>
            @error('category_id')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-4">
            <label class="field-label">Amount (P)</label>
            <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount', $item?->amount) }}" required>
            @error('amount')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-4">
            <label class="field-label">Frequency</label>
            @php($selectedFrequency = old('frequency', $item?->frequency ?? 'monthly'))
            <select name="frequency">
                <option value="weekly" {{ $selectedFrequency === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $selectedFrequency === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
            @error('frequency')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-4">
            <label class="field-label">Repeat every</label>
            <input type="number" name="interval" min="1" max="12" value="{{ old('interval', $item?->interval ?? 1) }}" required>
            @error('interval')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-4">
            <label class="field-label">Start date</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional($item?->start_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
            @error('start_date')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-4">
            <label class="field-label">Next due date</label>
            <input type="date" name="next_due_date" value="{{ old('next_due_date', optional($item?->next_due_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
            @error('next_due_date')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-4">
            <label class="field-label">Ends on</label>
            <input type="date" name="ends_on" value="{{ old('ends_on', optional($item?->ends_on)->format('Y-m-d')) }}">
            @error('ends_on')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label">Reminder lead time</label>
            <input type="number" name="reminder_days_before" min="0" max="14" value="{{ old('reminder_days_before', $item?->reminder_days_before ?? 3) }}" required>
            <p class="field-note">Number of days before the due date when the reminder notification appears.</p>
            @error('reminder_days_before')<p class="text-sm text-expense">{{ $message }}</p>@enderror
        </div>

        <div class="form-field md:col-span-6">
            <label class="field-label">Status</label>
            <input type="hidden" name="is_active" value="0">
            <label class="settings-choice-card">
                <input type="checkbox" name="is_active" value="1" class="settings-choice-input" {{ old('is_active', $item?->is_active ?? true) ? 'checked' : '' }}>
                <span class="settings-choice-copy">
                    <span class="settings-choice-title">Active schedule</span>
                    <span class="settings-choice-note">Paused items stay saved but do not show reminders or due actions.</span>
                </span>
            </label>
        </div>
    </div>

    <div class="page-actions mt-6">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('recurring-transactions.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
