<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $type
 * @property numeric $balance
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account withoutTrashed()
 */
	class Account extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property string|null $action_url
 * @property array<array-key, mixed>|null $data
 * @property string $fingerprint
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereActionUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereUserId($value)
 */
	class AppNotification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $year
 * @property int $month
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereYear($value)
 */
	class Budget extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $type
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $account_id
 * @property int $category_id
 * @property string $type
 * @property numeric $amount
 * @property string $description
 * @property string $frequency
 * @property int $interval
 * @property int $reminder_days_before
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $next_due_date
 * @property \Illuminate\Support\Carbon|null $ends_on
 * @property \Illuminate\Support\Carbon|null $last_processed_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereEndsOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereLastProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereNextDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereReminderDaysBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereUserId($value)
 */
	class RecurringTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $account_id
 * @property int $category_id
 * @property string $type
 * @property numeric $amount
 * @property string|null $description
 * @property string $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction withoutTrashed()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $theme_preference
 * @property string $font_size_preference
 * @property string|null $avatar_path
 * @property string|null $onboarding_step
 * @property \Illuminate\Support\Carbon|null $onboarding_completed_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AppNotification> $appNotifications
 * @property-read int|null $app_notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Budget> $budgets
 * @property-read int|null $budgets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecurringTransaction> $recurringTransactions
 * @property-read int|null $recurring_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFontSizePreference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOnboardingCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOnboardingStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereThemePreference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

