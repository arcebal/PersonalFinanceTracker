<?php

namespace Tests\Feature;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_page_is_displayed_with_existing_items(): void
    {
        $user = User::factory()->create();

        AppNotification::create([
            'user_id' => $user->id,
            'type' => 'budget_exceeded',
            'title' => 'Food budget exceeded',
            'message' => 'You are over budget by P220.00 this month.',
            'fingerprint' => 'budget_exceeded:test',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('notifications.index'));

        $response
            ->assertOk()
            ->assertSee('Food budget exceeded')
            ->assertSee('Unread summary');
    }

    public function test_user_can_mark_notifications_as_read(): void
    {
        $user = User::factory()->create();

        $first = AppNotification::create([
            'user_id' => $user->id,
            'type' => 'inactive_logging',
            'title' => 'Come back to your tracker',
            'message' => 'It has been 7 days since your last entry.',
            'fingerprint' => 'inactive:test:1',
        ]);

        $second = AppNotification::create([
            'user_id' => $user->id,
            'type' => 'recurring_due_soon',
            'title' => 'Rent is due soon',
            'message' => 'Expense of P1500.00 is scheduled soon.',
            'fingerprint' => 'inactive:test:2',
        ]);

        $this->actingAs($user)
            ->patch(route('notifications.read', $first))
            ->assertRedirect();

        $this->actingAs($user)
            ->patch(route('notifications.read-all'))
            ->assertRedirect();

        $this->assertNotNull($first->fresh()->read_at);
        $this->assertNotNull($second->fresh()->read_at);
    }
}
