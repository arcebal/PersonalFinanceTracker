<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'type',
        'amount',
        'description',
        'frequency',
        'interval',
        'reminder_days_before',
        'start_date',
        'next_due_date',
        'ends_on',
        'last_processed_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'start_date' => 'date',
            'next_due_date' => 'date',
            'ends_on' => 'date',
            'last_processed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isDue(?CarbonInterface $date = null): bool
    {
        $date ??= now();

        return $this->is_active
            && $this->next_due_date !== null
            && $this->next_due_date->startOfDay()->lte($date->copy()->startOfDay());
    }

    public function isDueSoon(?CarbonInterface $date = null): bool
    {
        $date ??= now();

        if (! $this->is_active || $this->next_due_date === null) {
            return false;
        }

        return $this->next_due_date->between(
            $date->copy()->startOfDay(),
            $date->copy()->addDays($this->reminder_days_before)->endOfDay()
        );
    }
}
