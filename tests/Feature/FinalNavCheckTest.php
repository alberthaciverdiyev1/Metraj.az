<?php

namespace Tests\Feature;

use App\Modules\Shared\Models\User;
use Tests\TestCase;

class FinalNavCheckTest extends TestCase
{
    public function test_agency_nav_has_realtors_item(): void
    {
        $this->actingAs(User::where('email', 'agency@metraj.az')->firstOrFail());
        $this->get('/agency')
            ->assertOk()
            ->assertSee('Rieltorlarım')
            ->assertSee('Elanlarım');
    }

    public function test_admin_agents_page_still_works(): void
    {
        $this->actingAs(User::where('email', User::ADMIN_EMAIL)->firstOrFail());
        $this->get('/admin/agents')->assertOk();
        $this->get('/admin/agencies')->assertOk();
        $this->get('/admin/users')->assertOk();
    }
}
