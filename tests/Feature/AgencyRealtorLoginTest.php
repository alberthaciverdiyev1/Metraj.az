<?php

namespace Tests\Feature;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AgencyRealtorLoginTest extends TestCase
{
    public function test_newly_created_realtor_can_login_to_agency_panel(): void
    {
        $owner = User::where('email', 'agency@metraj.az')->firstOrFail();

        // Simulate what CreateAgent does
        $user = User::create([
            'name' => 'Login Test Rieltor',
            'email' => 'login.rieltor.' . time() . '@metraj.az',
            'password' => Hash::make('password123'),
        ]);

        $agent = Agent::create([
            'agency_id' => $owner->tenantAgency()->id,
            'user_id' => $user->id,
            'position' => 'Test',
            'phone' => '+994 50 333 44 55',
            'is_active' => true,
        ]);

        // The new realtor can access the agency panel dashboard
        $this->actingAs($user);
        $this->get('/agency')->assertOk();

        // But cannot manage the agents resource (owner-only)
        $this->get('/agency/agents')->assertStatus(403);

        // cleanup
        $agent->forceDelete();
        $user->forceDelete();
    }

    public function test_edit_page_shows_linked_user_as_readonly(): void
    {
        $this->actingAs(User::where('email', 'agency@metraj.az')->firstOrFail());
        $own = Agent::where('agency_id', \App\Models\User::where('email', 'agency@metraj.az')->firstOrFail()->tenantAgency()->id)->first();
        $this->assertNotNull($own);

        $response = $this->get('/agency/agents/' . $own->id . '/edit');
        $response->assertOk();
        $response->assertSee($own->user->name);
    }
}
