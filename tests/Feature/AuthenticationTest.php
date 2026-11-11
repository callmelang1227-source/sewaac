<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_from_the_admin_panel(): void
    {
        $this->get(route('admin.home'))
            ->assertRedirectToRoute('login');
    }

    public function test_administrators_can_sign_in_and_access_the_admin_panel(): void
    {
        $administrator = User::factory()->administrator()->create([
            'password' => Hash::make('Correct-password-123'),
        ]);

        $this->post(route('login.store'), [
            'email' => $administrator->email,
            'password' => 'Correct-password-123',
        ])->assertRedirectToRoute('admin.home');

        $this->assertAuthenticatedAs($administrator);
        $this->get(route('admin.home'))->assertOk();
    }

    public function test_non_administrators_cannot_sign_in_to_the_admin_panel(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('Correct-password-123'),
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'Correct-password-123',
            ])
            ->assertRedirectToRoute('login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_non_administrators_are_forbidden_from_the_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.home'))
            ->assertForbidden();
    }

    public function test_administrators_can_log_out(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('logout'))
            ->assertRedirectToRoute('login');

        $this->assertGuest();
    }

    public function test_public_administrator_registration_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }
}
