<?php

namespace Tests\Feature;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Property;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentPagesSmokeTest extends TestCase
{
    public function test_admin_profile_page_renders(): void
    {
        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $this->assertNotNull($user, 'Admin user not seeded');

        $this->actingAs($user);
        Livewire::test(\App\Filament\Pages\EditProfile::class)
            ->assertOk()
            ->assertSee('Profilim');
    }

    public function test_admin_property_view_page_renders(): void
    {
        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $prop = Property::first();
        $this->assertNotNull($prop, 'No property seeded');

        $this->actingAs($user);
        Livewire::test(\App\Filament\Admin\Resources\PropertyResource\Pages\ViewProperty::class, ['record' => $prop->getKey()])
            ->assertOk()
            ->assertSee($prop->code);
    }

    public function test_agency_property_view_page_renders(): void
    {
        $user = User::where('email', 'agency@metraj.az')->first();
        $this->assertNotNull($user, 'Agency owner not seeded');

        $prop = Property::where('agency_id', $user->agencies()->first()?->id)->first();
        $this->assertNotNull($prop, 'No agency property seeded');

        $this->actingAs($user);
        Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\ViewProperty::class, ['record' => $prop->getKey()])
            ->assertOk()
            ->assertSee($prop->code);
    }

    public function test_agency_profile_page_shows_agent_section_for_realtor(): void
    {
        $user = User::whereHas('agent')->first();
        $this->assertNotNull($user, 'No realtor user seeded');

        $this->actingAs($user);
        Livewire::test(\App\Filament\Pages\EditProfile::class)
            ->assertOk()
            ->assertSee('Rieltor Profili');
    }
}
