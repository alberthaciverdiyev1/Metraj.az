<?php

namespace App\Filament\Agency\Resources\AgentResource\Pages;

use App\Filament\Agency\Resources\AgentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditAgent extends EditRecord
{
    protected static string $resource = AgentResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $agent = $this->getRecord();
        if ($agent->user) {
            $data['user_name'] = $agent->user->name;
            $data['user_email'] = $agent->user->email;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userName = $data['user_name'] ?? null;
        $userEmail = $data['user_email'] ?? null;
        $newPassword = $data['new_password'] ?? null;
        unset($data['user_name'], $data['user_email'], $data['new_password']);

        $data['agency_id'] = Auth::user()?->tenantAgency()?->id;

        $record->update($data);

        if ($record->user) {
            $userUpdate = [];
            if ($userName) {
                $userUpdate['name'] = $userName;
            }
            if ($userEmail) {
                $userUpdate['email'] = $userEmail;
            }
            if (! empty($newPassword)) {
                $userUpdate['password'] = Hash::make($newPassword);
            }
            if (! empty($userUpdate)) {
                $record->user->update($userUpdate);
            }
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
