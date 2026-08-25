<?php

namespace Tests\Feature;

use App\Modules\Shared\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentNavigationSmokeTest extends TestCase
{
    public function test_admin_sidebar_shows_profile_in_settings_group(): void
    {
        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $this->actingAs($user);

        $response = $this->get('/admin');
        $response->assertOk();
        $response->assertSee('Parametrlər');
        $response->assertSee('Profilim');
        $response->assertSee('/admin/profile');
    }

    public function test_agency_navbar_shows_profile_in_settings_group(): void
    {
        $user = User::where('email', 'agency@kibriskare.com')->first();
        $this->actingAs($user);

        $response = $this->get('/agency');
        $response->assertOk();
        $response->assertSee('Parametrlər');
        $response->assertSee('Profilim');
        $response->assertSee('/agency/profile');
    }

    public function test_admin_profile_url_resolves(): void
    {
        $admin = User::where('email', User::ADMIN_EMAIL)->first();
        $this->actingAs($admin);
        $this->get('/admin/profile')->assertOk();
    }

    public function test_agency_profile_url_resolves(): void
    {
        $agencyUser = User::where('email', 'agency@kibriskare.com')->first();
        $this->actingAs($agencyUser);
        $this->get('/agency/profile')->assertOk();
    }
}
