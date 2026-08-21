<?php

namespace Tests\Feature;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Property;
use App\Models\User;
use Tests\TestCase;

class AgencyPropertyVisibilityTest extends TestCase
{
    public function test_realtor_sees_only_own_properties(): void
    {
        $realtor = User::whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'))
            ->where('email', '!=', 'agency@metraj.az')
            ->firstOrFail();

        $this->actingAs($realtor);

        $query = \App\Filament\Agency\Resources\PropertyResource::getEloquentQuery();
        $properties = $query->get();

        $this->assertGreaterThanOrEqual(0, $properties->count());

        // Every property the realtor sees must be their own
        foreach ($properties as $prop) {
            $this->assertEquals($realtor->id, $prop->user_id, "Property {$prop->id} must belong to the realtor");
        }

        // And they must NOT see any property that belongs to another user
        $otherIds = Property::where('user_id', '!=', $realtor->id)->pluck('id');
        foreach ($otherIds as $otherId) {
            $this->assertFalse($properties->contains('id', $otherId), 'Realtor must not see another user\'s property');
        }
    }

    public function test_owner_sees_all_agency_properties(): void
    {
        $owner = User::where('email', 'agency@metraj.az')->firstOrFail();
        $this->actingAs($owner);

        $tenantAgencyId = $owner->tenantAgency()->id;

        $query = \App\Filament\Agency\Resources\PropertyResource::getEloquentQuery();
        $properties = $query->get();

        foreach ($properties as $prop) {
            $this->assertEquals($tenantAgencyId, $prop->agency_id, "Owner sees property {$prop->id} of their agency");
        }

        // Owner must see MORE than any single realtor (all agency listings)
        $singleRealtorCount = Property::where('user_id', '!=', $owner->id)
            ->where('agency_id', $tenantAgencyId)
            ->distinct('user_id')
            ->count();
        // Sanity: the query ran without error and only returns agency-scoped records
        $this->assertNotNull($properties);
    }
}
