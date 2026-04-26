<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/settings/profile');

        $response->assertOk();
    }

    public function test_guest_cannot_access_settings_routes(): void
    {
        $this->get('/settings/profile')->assertRedirect('/login');
        $this->patch('/settings/profile/appearance')->assertRedirect('/login');
        $this->post('/settings/profile/avatar')->assertRedirect('/login');
        $this->delete('/settings/profile/avatar')->assertRedirect('/login');
    }

    public function test_appearance_settings_can_be_updated(): void
    {
        $user = User::factory()->create([
            'theme_preference' => 'system',
            'font_size_preference' => 'default',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile/appearance', [
                'theme_preference' => 'dark',
                'font_size_preference' => 'large',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();

        $this->assertSame('dark', $user->theme_preference);
        $this->assertSame('large', $user->font_size_preference);
    }

    public function test_invalid_appearance_settings_are_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/settings/profile')
            ->patch('/settings/profile/appearance', [
                'theme_preference' => 'midnight',
                'font_size_preference' => 'huge',
            ]);

        $response
            ->assertSessionHasErrors(['theme_preference', 'font_size_preference'])
            ->assertRedirect('/settings/profile');
    }

    public function test_avatar_can_be_uploaded(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/settings/profile/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.png'),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();

        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_avatar_replacement_deletes_the_old_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post('/settings/profile/avatar', [
            'avatar' => UploadedFile::fake()->image('first.png'),
        ]);

        $oldPath = $user->fresh()->avatar_path;

        $this->actingAs($user)->post('/settings/profile/avatar', [
            'avatar' => UploadedFile::fake()->image('second.png'),
        ]);

        $newPath = $user->fresh()->avatar_path;

        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_avatar_can_be_removed(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user)->post('/settings/profile/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.png'),
        ]);

        $avatarPath = $user->fresh()->avatar_path;

        $response = $this
            ->actingAs($user)
            ->delete('/settings/profile/avatar');

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $this->assertNull($user->fresh()->avatar_path);
        Storage::disk('public')->assertMissing($avatarPath);
    }
}
