<?php

namespace Tests\Feature;

use App\Modules\Shared\Models\User;
use Tests\TestCase;

class AgencyNavVisibilityTest extends TestCase
{
    public function test_owner_sees_realtors_nav_item(): void
    {
        $owner = User::where('email', 'agency@kibriskare.com')->firstOrFail();
        $this->actingAs($owner);
        $this->get('/agency')
            ->assertOk()
            ->assertSee('Rieltorlarım');
    }

    public function test_realtor_does_not_see_realtors_nav_item(): void
    {
        $realtor = User::whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'))
            ->where('email', '!=', 'agency@kibriskare.com')
            ->firstOrFail();

        $this->actingAs($realtor);
        $response = $this->get('/agency')->assertOk();
        $response->assertDontSee('Rieltorlarım');
    }

    public function test_realtor_still_sees_own_properties_nav(): void
    {
        $realtor = User::whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'))
            ->where('email', '!=', 'agency@kibriskare.com')
            ->firstOrFail();

        $this->actingAs($realtor);
        $this->get('/agency')->assertOk()->assertSee('Elanlarım');
    }
}
