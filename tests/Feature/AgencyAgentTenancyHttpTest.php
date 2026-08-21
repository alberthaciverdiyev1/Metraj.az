<?php

namespace Tests\Feature;

use App\Modules\Agency\Models\Agent;
use App\Modules\Shared\Models\User;
use Tests\TestCase;

class AgencyAgentTenancyHttpTest extends TestCase
{
    private function owner(): User
    {
        return User::where('email', 'agency@metraj.az')->firstOrFail();
    }

    public function test_agency_agents_list_over_http(): void
    {
        $this->actingAs($this->owner());
        $this->get('/agency/agents')->assertOk();
    }

    public function test_agency_agents_create_page_over_http(): void
    {
        $this->actingAs($this->owner());
        $this->get('/agency/agents/create')->assertOk();
    }

    public function test_agency_agent_view_page_over_http(): void
    {
        $this->actingAs($this->owner());
        $own = Agent::where('agency_id', $this->owner()->tenantAgency()->id)->first();
        $this->assertNotNull($own);
        $this->get('/agency/agents/' . $own->id)->assertOk();
    }

    public function test_agency_agent_edit_page_over_http(): void
    {
        $this->actingAs($this->owner());
        $own = Agent::where('agency_id', $this->owner()->tenantAgency()->id)->first();
        $this->get('/agency/agents/' . $own->id . '/edit')->assertOk();
    }

    public function test_agency_owner_cannot_open_foreign_realtor_edit_page(): void
    {
        $this->actingAs($this->owner());
        $tenantId = $this->owner()->tenantAgency()->id;
        $foreign = Agent::where('agency_id', '!=', $tenantId)->first();
        $this->assertNotNull($foreign);

        // Filament aborts with 404 on canEdit() failure (avoids leaking record existence)
        $this->get('/agency/agents/' . $foreign->id . '/edit')
            ->assertStatus(404);
    }

    public function test_realtor_cannot_access_agents_resource(): void
    {
        // A realtor (not owner) must NOT manage other realtors
        $realtor = User::whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'))
            ->where('email', '!=', 'agency@metraj.az')
            ->first();
        $this->assertNotNull($realtor);

        $this->actingAs($realtor);

        // List, create, view, edit all forbidden for a realtor
        $this->get('/agency/agents')->assertStatus(403);
        $this->get('/agency/agents/create')->assertStatus(403);
        $this->get('/agency/agents/' . $realtor->agent->id . '/edit')->assertStatus(403);
    }
}
