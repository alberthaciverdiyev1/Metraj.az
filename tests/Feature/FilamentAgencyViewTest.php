<?php

namespace Tests\Feature;

use App\Modules\Agency\Models\Agency;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentAgencyViewTest extends TestCase
{
    private function admin(): User
    {
        return User::where('email', User::ADMIN_EMAIL)->firstOrFail();
    }

    public function test_admin_agency_view_page_renders_with_all_sections(): void
    {
        $this->actingAs($this->admin());
        $agency = Agency::first();
        $this->assertNotNull($agency, 'No agency seeded');

        Livewire::test(\App\Filament\Admin\Resources\AgencyResource\Pages\ViewAgency::class, ['record' => $agency->getKey()])
            ->assertOk()
            ->assertSee('Agentlik Məlumatları')
            ->assertSee('Loqo və Banner')
            ->assertSee('Əlaqə və Ünvan')
            ->assertSee('Statistika')
            ->assertSee($agency->name);
    }

    public function test_admin_agency_properties_relation_manager_renders(): void
    {
        $this->actingAs($this->admin());
        $agency = Agency::first();
        $this->assertNotNull($agency);

        Livewire::test(\App\Filament\Admin\Resources\AgencyResource\RelationManagers\PropertiesRelationManager::class, [
            'ownerRecord' => $agency,
            'pageClass' => \App\Filament\Admin\Resources\AgencyResource\Pages\ViewAgency::class,
        ])
            ->assertOk()
            ->assertSee('Agentliyin Elanları');
    }

    public function test_admin_agency_list_view_action_links_to_view_page(): void
    {
        $this->actingAs($this->admin());
        $agency = Agency::first();

        $this->get('/admin/agencies')
            ->assertOk()
            ->assertSee(route('filament.admin.resources.agencies.view', ['record' => $agency]));
    }
}
