<?php

namespace App\Filament\Agency\Resources\AgentResource\Pages;

use App\Filament\Agency\Resources\AgentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateAgent extends CreateRecord
{
    protected static string $resource = AgentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name' => $data['new_user_name'],
            'email' => $data['new_user_email'],
            'password' => Hash::make($data['new_user_password']),
        ]);

        unset($data['new_user_name'], $data['new_user_email'], $data['new_user_password']);

        $data['user_id'] = $user->id;
        $data['agency_id'] = Auth::user()?->tenantAgency()?->id;

        return $data;
    }
}
