<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_student_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }

    public function test_student_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }

    public function test_student_cannot_access_instructor_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/instructor');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin');

        // Filament redirects to login or dashboard, not 403
        $this->assertNotEquals(403, $response->getStatusCode());
    }

    public function test_instructor_can_access_instructor_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('instructor');

        $response = $this->actingAs($user)->get('/instructor');

        $this->assertNotEquals(403, $response->getStatusCode());
    }

    public function test_guest_cannot_access_journal(): void
    {
        $response = $this->get('/journal');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_bitacora(): void
    {
        $response = $this->get('/bitacora');

        $response->assertRedirect('/login');
    }

    public function test_guest_can_access_public_pages(): void
    {
        $this->get('/')->assertOk();
        $this->get('/nosotros')->assertOk();
        $this->get('/cursos')->assertOk();
        $this->get('/planes')->assertOk();
        $this->get('/ranking')->assertOk();
    }

    public function test_authenticated_user_can_access_protected_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/perfil')->assertOk();
        $this->actingAs($user)->get('/logros')->assertOk();
    }
}
