<?php

namespace Tests\Feature;

use App\Modules\Shared\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PlainUserAgencyLoginTest extends TestCase
{
    public function test_plain_new_user_cannot_access_agency_panel(): void
    {
        $email = 'plain.user.' . time() . '@kibriskare.com';

        // A user with NO agent record and NO owned agency (e.g. created via normal registration)
        $user = User::create([
            'name' => 'Plain User',
            'email' => $email,
            'password' => Hash::make('password123'),
        ]);

        $this->assertNull($user->agent, 'No agent record');
        $this->assertFalse($user->agencies()->exists(), 'No owned agency');

        // canAccessPanel must deny them from the agency panel
        Filament::setCurrentPanel(Filament::getPanel('agency'));
        $this->assertFalse($user->canAccessPanel(Filament::getCurrentPanel()));

        // Cleanup
        $user->forceDelete();
    }

    public function test_plain_user_cannot_access_admin_panel(): void
    {
        $email = 'plain.user.admin.' . time() . '@kibriskare.com';
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
