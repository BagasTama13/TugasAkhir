<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'owner']);
        Role::firstOrCreate(['name' => 'worker']);
        Role::firstOrCreate(['name' => 'user']);
    }

    public function test_admin_can_access_admin_dashboard_but_not_user_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertOk();

        $response = $this->actingAs($admin)->get('/user/dashboard');
        $response->assertStatus(403);
    }

    public function test_user_can_access_user_dashboard_but_not_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->first());

        $response = $this->actingAs($user)->get('/user/dashboard');
        $response->assertOk();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(403);
    }

    public function test_owner_can_access_owner_dashboard(): void
    {
        $owner = User::factory()->create(['username' => 'owner1']);
        $owner->roles()->attach(Role::where('name', 'owner')->first());

        $response = $this->actingAs($owner)->get('/owner/owner1/dashboard');
        $response->assertOk();
    }

    public function test_worker_can_access_worker_dashboard(): void
    {
        $worker = User::factory()->create(['username' => 'worker1']);
        $worker->roles()->attach(Role::where('name', 'worker')->first());

        $response = $this->actingAs($worker)->get('/worker/worker1/dashboard');
        $response->assertOk();
    }
}
