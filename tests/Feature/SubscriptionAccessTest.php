<?php

namespace Tests\Feature;

use App\Models\AccessGrant;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SubscriptionAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_has_premium_access(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasPremiumAccess());
    }

    public function test_instructor_has_premium_access(): void
    {
        $user = User::factory()->create();
        $user->assignRole('instructor');

        $this->assertTrue($user->hasPremiumAccess());
    }

    public function test_student_without_subscription_has_no_premium_access(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $this->assertFalse($user->hasPremiumAccess());
    }

    public function test_student_with_access_grant_has_premium_access(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        $user->assignRole('student');

        AccessGrant::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'granted_by' => $admin->id,
            'status' => 'active',
            'duration_type' => 'lifetime',
            'starts_at' => now(),
            'token' => Str::random(64),
        ]);

        $this->assertTrue($user->hasPremiumAccess());
    }

    public function test_expired_access_grant_has_no_premium_access(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        $user->assignRole('student');

        AccessGrant::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'granted_by' => $admin->id,
            'status' => 'active',
            'duration_type' => '1_month',
            'starts_at' => now()->subMonths(2),
            'expires_at' => now()->subDay(),
            'token' => Str::random(64),
        ]);

        $this->assertFalse($user->hasPremiumAccess());
    }

    public function test_free_course_accessible_without_premium(): void
    {
        $user = User::factory()->create();

        $course = Course::factory()->create(['access_type' => 'free']);

        $this->assertTrue($user->canAccessCourse($course));
    }

    public function test_premium_course_not_accessible_without_premium(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $course = Course::factory()->create(['access_type' => 'premium']);

        $this->assertFalse($user->canAccessCourse($course));
    }

    public function test_premium_course_accessible_for_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $course = Course::factory()->create(['access_type' => 'premium']);

        $this->assertTrue($user->canAccessCourse($course));
    }

    public function test_journal_shows_upsell_for_non_premium_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/journal');

        $response->assertOk();
        $response->assertViewIs('journal.upsell');
    }
}
