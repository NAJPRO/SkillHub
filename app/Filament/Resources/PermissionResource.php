<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\PermissionResource\Pages\ListPermissions;
use App\Models\Categorie;
use Spatie\Permission\Models\Role;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    //protected static ?string $pluralLabel = 'Permissions';


    public static function getNavigationLabel(): string
    {
        return __('menu.permissions');
    }
    public static function getPluralLabel(): string
    {
        return __('menu.permissions');
    }

    public static function canViewAny(): bool
    {
        // Verifie si l'utilisateur peut acceder a l'interface de gestion des utilisateurs
        /** @var User $user */
        $user = Auth::user();
        return $user->can('manage_permission');
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('permissions.title'))
                    ->formatStateUsing(fn ($state) => __('permissions.'.$state)) // Traduire le nom de permission
                    ->sortable()
                    ->wrapHeader()
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

                // Ajouter les colonnes pour gérer les permissions par rôle
                ...self::getRoleColumns(),
            ])
            ->filters([

            ])


            ->actions([

            ])
            ->defaultSort('name')
            ->paginated(10);
    }

    /**
     * Génère dynamiquement les colonnes de checkboxes pour chaque rôle.
     */
    protected static function getRoleColumns(): array
    {
        $roles = Role::all();
        return $roles->map(function ($role) {
            return CheckboxColumn::make("roles.{$role->name}")
                ->label(__('users.role.'.$role->name))
                ->alignment(Alignment::Center)
           //     ->onIcon('heroicon-s-lock-open')
             //   ->offIcon('heroicon-m-lock-closed')
                ->disabled(fn () => $role->name === 'super-admin') // Désactiver pour Super Admin
                ->getStateUsing(fn (Permission $record) => $record->roles->contains('name', $role->name)) // Initialise l'état
                ->beforeStateUpdated(function (Permission $record, bool $state) use ($role) {
                    DB::transaction(function () use ($state, $record, $role) {
                        if ($state) {
                            $role->givePermissionTo($record->name);
                            \Filament\Notifications\Notification::make()
                                ->title(__('permissions.notification.add_permission'))
                                ->color('success')
                                ->success()
                                ->send();
                        } else {
                            $role->revokePermissionTo($record->name);
                            \Filament\Notifications\Notification::make()
                                ->title(__('permissions.notification.revoke_permission'))
                                ->success()
                                ->send();
                        }
                    });


                    // Rafraîchir les relations pour éviter toute désynchronisation
                    $record->refresh();

                });
        })->toArray();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
        ];
    }
}
