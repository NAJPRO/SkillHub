<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ManageUserPermissions extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.manage-user-permissions';

    public User $user;

    public function mount(int $record): void
    {
        $this->user = User::findOrFail($record);
    }

    public function getTitle(): string
    {
        $who_role = __('users.role.'. $this->user->role);
        return __('permissions.manage_permission_for_user_title', ['name' => $this->user->name." [{$who_role}]"]);
    }

    protected function getTableQuery()
    {
        return Permission::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('permissions.title'))
                ->formatStateUsing(fn ($state) => __('permissions.' . $state))
                ->sortable()
                ->searchable(query: function (Builder $query, string $search) {
                    // Charge toutes les traductions
                    $translations = collect(__('permissions'));

                    // Filtre les traductions pour ne garder que les chaînes de caractères
                    $translatedPermissions = $translations
                        ->filter(fn ($value) => is_string($value)) // Ignore les tableaux
                        ->filter(fn ($translatedValue, $key) => str_contains(strtolower($translatedValue), strtolower($search)))
                        ->keys();

                    // Applique la recherche sur les clés trouvées
                    $query->whereIn('name', $translatedPermissions);
                }),

            TextColumn::make('status')
                ->label('')
                ->formatStateUsing(fn ($record) => $this->user->hasDirectPermission($record->name)
                    ? __('permissions.has_permission')
                    : __('permissions.no_permission'))
                ->badge()
                ->color(fn ($record) => $this->user->hasDirectPermission($record->name) ? 'success' : 'danger'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('togglePermission')
            ->label(fn ($record) => $this->user->roles->pluck('permissions')->flatten()->contains('name', $record->name)
            ? __('permissions.lock_permission_role')
            : ($this->user->hasDirectPermission($record->name)
                ? __('permissions.remove_permission')
                : __('permissions.add_permission')
            )
        )

        ->icon(fn ($record) => $this->user->roles->pluck('permissions')->flatten()->contains('name', $record->name)
            ? 'heroicon-s-lock-closed' // Icône pour permission verrouillée par un rôle
            : ($this->user->hasDirectPermission($record->name)
                ? 'heroicon-s-lock-closed' // Icône pour permission attribuée directement
                : 'heroicon-m-lock-open' // Icône pour permission non attribuée
            )
        )

        ->color(fn ($record) => $this->user->roles->pluck('permissions')->flatten()->contains('name', $record->name)
            ? 'gray' // Couleur pour permission verrouillée par un rôle
            : ($this->user->hasDirectPermission($record->name)
                ? 'danger' // Couleur pour permission attribuée directement
                : 'success' // Couleur pour permission non attribuée
            )
        )

                ->disabled(fn ($record) => $this->user->roles->pluck('permissions')->flatten()->contains('name', $record->name))
                ->action(function ($record) {
                    DB::transaction(function () use ($record) {
                        if ($this->user->hasDirectPermission($record->name)) {
                            $this->user->revokePermissionTo($record->name);
                            Notification::make()
                                ->title(__('permissions.notification.revoke_permission'))
                                ->color('danger')
                                ->send();
                        } else {
                            $this->user->givePermissionTo($record->name);
                            Notification::make()
                                ->title(__('permissions.notification.add_permission'))
                                ->color('success')
                                ->send();
                        }
                    });

                    $this->user->refresh();
                }),
        ];
    }
}
