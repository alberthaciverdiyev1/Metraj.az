<?php

namespace Tests\Feature;

use App\Modules\Agency\Models\Agent;
use App\Modules\Shared\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class RealtorLoginRegressionTest extends TestCase
{
    /**
     * Regression: yeni yaradılan rieltor hesabı ilə agency paneline giriş.
     */
    public function test_agency_created_realtor_can_login(): void
    {
        $owner = User::where('email', 'agency@kibriskare.com')->firstOrFail();
        $email = 'regression.' . time() . '@kibriskare.com';
        $password = 'sifre12345';

        // Create realtor exactly like CreateAgent::mutateFormDataBeforeCreate
        $user = User::create([
            'name' => 'Regression Rieltor',
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        Agent::create([
            'agency_id' => $owner->tenantAgency()->id,
            'user_id' => $user->id,
            'position' => 'Test',
            'phone' => '+994 50 888 99 00',
            'is_active' => true,
        ]);

        // Login to agency panel with the new realtor
        Filament::setCurrentPanel(Filament::getPanel('agency'));
        Livewire::test(\Filament\Pages\Auth\Login::class)
            ->fillForm([
                'email' => $email,
                'password' => $password,
                'remember' => false,
            ])
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
        $this->get('/agency')->assertOk();

        // cleanup
        $user->agent()->forceDelete();
        $user->forceDelete();
    }
}
