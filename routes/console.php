<?php

use App\Services\NotificationGeneratorService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('finance:generate-recurring-reminders', function (NotificationGeneratorService $service) {
    $created = $service->generateRecurringDueSoonNotifications();

    $this->info("Created {$created} recurring reminder notification(s).");
})->purpose('Generate recurring due-soon reminders');

Artisan::command('finance:generate-budget-notifications', function (NotificationGeneratorService $service) {
    $created = $service->generateBudgetNotifications();

    $this->info("Created {$created} budget notification(s).");
})->purpose('Generate budget health notifications');

Artisan::command('finance:generate-inactivity-notifications', function (NotificationGeneratorService $service) {
    $created = $service->generateInactivityNotifications();

    $this->info("Created {$created} inactivity notification(s).");
})->purpose('Generate inactivity notifications');

Artisan::command('finance:generate-month-end-summaries', function (NotificationGeneratorService $service) {
    $created = $service->generateMonthEndSummaryNotifications();

    $this->info("Created {$created} month-end summary notification(s).");
})->purpose('Generate month-end summary notifications');

Schedule::command('finance:generate-recurring-reminders')->dailyAt('08:00');
Schedule::command('finance:generate-budget-notifications')->dailyAt('08:10');
Schedule::command('finance:generate-inactivity-notifications')->dailyAt('09:00');
Schedule::command('finance:generate-month-end-summaries')->dailyAt('09:10');
