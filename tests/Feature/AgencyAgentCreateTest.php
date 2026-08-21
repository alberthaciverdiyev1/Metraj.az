<?php

namespace Tests\Feature;

use App\Modules\Agency\Models\Agent;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class AgencyAgentCreateTest extends TestCase
{
    private function owner(): User
    {
        return User::where('email', 'agency@metraj.az')->firstOrFail();
    }

    private function setUpAgencyPanel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('agency'));
    }

    public function test_agency_create_realtor_form_has_no_user_select(): void
    {
        $this->setUpAgencyPanel();
        $this->actingAs($this->owner());

        $component = Livewire::test(\App\Filament\Agency\Resources\AgentResource\Pages\CreateAgent::class);

        // There must be NO way to pick an existing user
        $state = $component->get('data');
        $this->assertArrayNotHasKey('user_id', $state, 'user_id select must NOT exist');

        // New-user fields must be present
        $this->assertArrayHasKey('new_user_name', $state);
        $this->assertArrayHasKey('new_user_email', $state);
        $this->assertArrayHasKey('new_user_password', $state);
    }

    public function test_agency_owner_creates_realtor_with_new_user(): void
    {
        $this->setUpAgencyPanel();
        $this->actingAs($this->owner());

        $tenantAgencyId = $this->owner()->tenantAgency()->id;
        $email = 'yeni.rieltor.' . time() . '@metraj.az';

        Livewire::test(\App\Filament\Agency\Resources\AgentResource\Pages\CreateAgent::class)
            ->fillForm([
                'new_user_name' => 'Yeni Rieltor',
                'new_user_email' => $email,
                'new_user_password' => 'password123',
                'position' => 'Satış Mütəxəssisi',
                'phone' => '+994 50 111 22 33',
                'whatsapp' => '+994 50 111 22 33',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        // A brand-new user was created
        $newUser = User::where('email', $email)->first();
        $this->assertNotNull($newUser, 'New user should be auto-created');
        $this->assertEquals('Yeni Rieltor', $newUser->name);
        $this->assertNotEquals('password123', $newUser->password, 'Password must be hashed');

        // Agent is linked to the new user and forced to tenant agency
        $created = Agent::where('user_id', $newUser->id)->first();
        $this->assertNotNull($created, 'Agent should be created');
        $this->assertEquals($tenantAgencyId, $created->agency_id, 'agency_id must be forced to tenant agency');

        // cleanup
        $created->forceDelete();
        $newUser->forceDelete();
    }

    public function test_agency_owner_cannot_reuse_existing_user_email(): void
    {
        $this->setUpAgencyPanel();
        $this->actingAs($this->owner());

        // Try to create a realtor with an email that already exists
        Livewire::test(\App\Filament\Agency\Resources\AgentResource\Pages\CreateAgent::class)
            ->fillForm([
                'new_user_name' => 'Duplikat',
                'new_user_email' => 'agency@metraj.az',
                'new_user_password' => 'password123',
                'position' => 'Test',
                'phone' => '+994 50 555 66 77',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['new_user_email' => 'unique']);

        // No agent was created
        $this->assertEquals(0, Agent::where('phone', '+994 50 555 66 77')->count());
    }
}
