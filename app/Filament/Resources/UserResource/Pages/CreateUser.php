<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public ?array $data = [];



    public function getTitle(): string
    {
        return __('users.create_title');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        return $data;
    }


    public function mount(): void
    {
        parent::mount();
        // Initialisation
        $this->form->fill([
            'status' => 'active',
            'role' => 'client'
        ]);

        // Initialisation des propriétés dynamiques
    }
    protected function afterCreate(): void
    {
        dd($this->record);
        $this->record->assignRole($this->record->role);
        /*Notification::make()
            ->success()
            ->title(__('user.notification.create_user_title'))
            ->body(__('user.notification.create_user_message'))
            ->send();*/
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('users.notification.create_user_title'))
            ->body(__('users.notification.create_user_message'));
    }
}
