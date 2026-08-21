<?php

namespace Tests\Feature;

use App\Modules\Property\Models\Property;
use App\Modules\Shared\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class AgencyPropertyTenancyTest extends TestCase
{
    private function owner(): User
    {
        return User::where('email', 'agency@metraj.az')->firstOrFail();
    }

    private function setUpAgencyPanel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('agency'));
    }

    public function test_agency_owner_sees_only_own_agency_properties(): void
    {
        $this->actingAs($this->owner());

        $query = \App\Filament\Agency\Resources\PropertyResource::getEloquentQuery();
        $tenantAgencyId = $this->owner()->tenantAgency()->id;

        foreach ($query->get() as $prop) {
            $this->assertTrue(
                $prop->user_id === $this->owner()->id || $prop->agency_id === $tenantAgencyId,
                "Property {$prop->id} must belong to owner or tenant agency"
            );
        }
    }

    public function test_agency_created_property_gets_ownership_assigned(): void
    {
        $this->setUpAgencyPanel();
        $this->actingAs($this->owner());

        $tenantAgencyId = $this->owner()->tenantAgency()->id;

        Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'price' => 100000,
                'currency' => 'AZN',
                'area' => 85,
                'rooms' => 2,
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $prop = Property::where('user_id', $this->owner()->id)->latest('id')->first();
        $this->assertNotNull($prop, 'Property should be created');
        $this->assertEquals($tenantAgencyId, $prop->agency_id, 'agency_id assigned to tenant');
        $this->assertEquals($this->owner()->agent?->id, $prop->agent_id, 'agent_id assigned to owner\'s realtor profile');
        $this->assertEquals('pending_approval', $prop->status->value, 'status defaults to pending approval');

        // cleanup
        $prop->filterOptions()->detach();
        $prop->images()->delete();
        $prop->forceDelete();
    }

    public function test_realtor_created_property_gets_ownership_assigned(): void
    {
        $this->setUpAgencyPanel();

        $realtor = User::whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'))
            ->where('email', '!=', 'agency@metraj.az')
            ->firstOrFail();
        $this->actingAs($realtor);

        $tenantAgencyId = $realtor->tenantAgency()->id;
        $this->assertNotNull($tenantAgencyId, 'Realtor must have a tenant agency');

        Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'price' => 50000,
                'currency' => 'AZN',
                'area' => 50,
                'rooms' => 1,
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $prop = Property::where('user_id', $realtor->id)->latest('id')->first();
        $this->assertNotNull($prop);
        $this->assertEquals($tenantAgencyId, $prop->agency_id);
        $this->assertEquals($realtor->agent->id, $prop->agent_id);
        $this->assertEquals('pending_approval', $prop->status->value);

        // cleanup
        $prop->filterOptions()->detach();
        $prop->images()->delete();
        $prop->forceDelete();
    }
}
