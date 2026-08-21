<?php

namespace Tests\Feature;

use App\Modules\Shared\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PlainUserAgencyLoginTest extends TestCase
{
    public function test_plain_new_user_can_login_to_agency_panel(): void
    {
        $email = 'plain.user.' . time() . '@metraj.az';

        // A user with NO agent record and NO owned agency (e.g. created via admin UserResource)
        $user = User::create([
            'name' => 'Plain User',
            'email' => $email,
            'password' => Hash::make('password123'),
        ]);

        $this->assertNull($user->agent, 'No agent record');
        $this->assertFalse($user->agencies()->exists(), 'No owned agency');

        // canAccessPanel must allow them into the agency panel
        Filament::setCurrentPanel(Filament::getPanel('agency'));
        $this->assertTrue($user->canAccessPanel(Filament::getCurrentPanel()));

        // Real login through the agency panel
        Filament::setCurrentPanel(Filament::getPanel('agency'));
        Livewire::test(\Filament\Pages\Auth\Login::class)
            ->fillForm([
                'email' => $email,
                'password' => 'password123',
                'remember' => false,
            ])
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);

        // The agency panel dashboard is accessible
        $this->get('/agency')->assertOk();

        // Cleanup
        $user->forceDelete();
    }

    public function test_plain_user_cannot_access_admin_panel(): void
    {
        $email = 'plain.user.admin.' . time() . '@metraj.az';
        $user = User::create([
            'name' => 'Plain User Admin',
            'email' => $email,
            'password' => Hash::make('password123'),
        ]);

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->assertFalse($user->canAccessPanel(Filament::getCurrentPanel()));

        $user->forceDelete();
    }
}
