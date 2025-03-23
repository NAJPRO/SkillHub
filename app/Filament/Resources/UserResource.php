<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\ManageUserPermissions;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'title';
    protected static int $globalSearchResultsLimit = 20; // Limite le nombre de résultat pour la Global Search
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.users');
    }
    public static function getPluralLabel(): string
    {
        return __('menu.users');
    }

    public static function canViewAny(): bool
    {
        // Verifie si l'utilisateur peut acceder a l'interface de gestion des utilisateurs
        /** @var User $user */
        $user = Auth::user();
        return $user->can('manage_users');
    }

    public static function canCreate(): bool
    {
        // Verifie si l'utilisateur peut creer un utilisateur
        /** @var User $user */
        $user = Auth::user();
        return $user->can('create_users');
    }

    public static function canDeleteRecord(Model $record): bool
    {
        // Verifie si l'utilisateur peut supprimer un utilisateur
        /** @var User $user */
        $user = Auth::user();
        return $user->can('delete_users');
    }

    public static function canUpdate(): bool
    {
        // Verifie si l'utilisateur peut modifier un utilisateur
        /** @var User $user */
        $user = Auth::user();
        return $user->can('update_users');
    }

    public static function canViewRecord(Model $record): bool
    {
        // Verifie si l'utilisateur peut voir un utilisateur
        /** @var User $user */
        $user = Auth::user();
        return $user->can('view_users');
    }




    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Grid::make(3) // Passer à une grille de 3 colonnes
            ->schema([
                Section::make(__('users.section.general'))
                    ->description(__('users.section.general_description'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('users.table.nom'))
                            ->required()
                            ->autocapitalize('words')
                            ->autocomplete('given-name')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label(__('users.table.email'))
                            ->email()
                            ->autocomplete('username')
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true) // Ignore l'ID de l'utilisateur actuel en modification
                            ->maxLength(255),
                    ])
                    ->columnSpan(2), // La première section prend 2 colonnes

                Section::make(__('users.table.photo'))
                    ->description(__('users.section.general_description'))
                    ->schema([
                        FileUpload::make('profile_picture')
                        ->label(__('users.table.photo'))
                        ->image()
                        ->avatar()
                        ->directory('avatars')
                    ])->columnSpan(1), // La deuxième section prend une colonne
            ]),

            Grid::make(2)
            ->schema([
                Section::make(__('users.section.contact'))
                ->description(__('users.section.contact_description'))
                ->schema([
                    TextInput::make('link_site_web')
                        ->label(__('users.table.link_site_web'))
                        ->url()
                        ->maxLength(255),
                    TextInput::make('link_github')
                        ->label(__('users.table.link_github'))
                        ->url()
                        ->maxLength(255),
                ])->columnSpan(1),

                Section::make(__('users.section.social'))
                    ->description(__('users.section.social_description'))
                    ->schema([
                        TextInput::make('link_linkedin')
                            ->label(__('users.table.link_linkedin'))
                            ->url()
                            ->maxLength(255),

                        TextInput::make('link_instagram')
                            ->label(__('users.table.link_instagram'))
                            ->url()
                            ->maxLength(255),
                    ])->columnSpan(1),
            ]),


            Grid::make(2)
            ->schema([
                Section::make(__('users.section.settings'))
                ->description(__('users.section.settings_description'))
                ->schema([
                    Select::make('role')
                    ->label(__('users.table.role'))
                    ->options(function(){
                        /**
                         * @var User $user
                         */
                        $user = Auth::user();
                        $typeUserFilters = [];
                        if($user->can('manage_admin')){
                            $typeUserFilters['admin'] = __('users.role.admin');
                        }
                        if($user->can('manage_freelance')){
                            $typeUserFilters['freelance'] = __('users.role.freelance');
                        }
                        if($user->can('manage_users')){
                            $typeUserFilters['client'] = __('users.role.client');
                        }
                        return $typeUserFilters;
                    })
                    ->native(false)
                    ->default('client')
                   // ->disabled(fn() => !auth()->user()->can('manage_users'))
                    ->required(),

                    Select::make('status')
                        ->label(__('users.table.status'))
                        ->options([
                            'active' => __('users.status.active'),
                            'inactive' => __('users.status.inactive'),
                            'suspended' => __('users.status.suspended'),
                            'banished' => __('users.status.banished'),
                            'not-verified' => __('users.status.not-verified'),
                        ])
                        ->native(false)
                        ->default('active')
                        ->disabled(fn() => !auth()->user()->can('manage_users'))
                        ->required(),
                ])->columnSpan(1),

                Section::make(__('users.section.security'))
                    ->description(__('users.section.security_description'))
                    ->schema([
                        TextInput::make('password')
                            ->label(__('users.password'))
                            ->password()
                            ->required(fn($record) => $record === null) // Requis uniquement en création
                            ->minLength(8)
                            ->confirmed()
                            ->revealable()
                            ->dehydrated(fn($state) => filled($state)) // Ne pas envoyer si vide
                            ->autocomplete('new-password'),

                            //->nullable(), // Permet de ne rien envoyer si vide

                        TextInput::make('password_confirmation')
                            ->label(__('users.password_confirm'))
                            ->password()
                            ->revealable()
                            ->required(fn($record) => $record === null)
                            ->dehydrated(false) // Ne pas stocker en base
                            ->autocomplete('new-password'),
                    ])->columnSpan(1),
            ])




        ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_picture')
                ->label(__('users.table.photo'))
                ->circular(),

                TextColumn::make('name')
                    ->label(__('users.table.nom'))
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => route('filament.admin.resources.users.view', ['record' => $record]))
                    ->color('info')
                    ->placeholder(__('users.default')),

                TextColumn::make('email')
                    ->label(__('users.table.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)

                    ->placeholder(__('users.default')),

                TextColumn::make('status')
                    ->label(__('users.table.status'))
                    ->formatStateUsing(fn ($state) => __('users.status.' . $state))
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'active' => 'heroicon-o-check-circle',
                        'inactive' => 'heroicon-o-clock',
                        'suspended' => 'heroicon-o-x-circle',
                        'banished' => 'heroicon-c-no-symbol',
                        'not-verified' => 'heroicon-o-exclamation-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'suspended' => 'danger',
                        'banished' => 'secondary',
                        'not-verified' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('role')
                    ->label(__('users.table.role'))
                    ->formatStateUsing(fn ($state) => __('users.role.' . $state))
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'client' => 'heroicon-o-user',
                        'freelance' => 'heroicon-o-user-group',
                        'admin' => 'heroicon-o-shield-check',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'client' => 'primary',
                        'freelance' => 'success',
                        'admin' => 'info',

                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('link_site_web')
                    ->label(__('users.table.link_site_web'))
                    ->toggleable(isToggledHiddenByDefault: true)  // Désactivé par défaut
                    ->url(fn ($record) => $record->link_site_web)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt') // Icône pour le site web
                    ->color('info')
                    ->placeholder(__('users.default')),

                TextColumn::make('link_github')
                    ->label(__('users.table.link_github'))
                    ->toggleable(isToggledHiddenByDefault: true) // Désactivé par défaut
                    ->url(fn ($record) => $record->link_github)
                    ->openUrlInNewTab()
                    ->icon('ri-github-fill') // Icône pour GitHub
                    ->color('info')
                    ->placeholder(__('users.default')),

                TextColumn::make('link_linkedin')
                    ->label(__('users.table.link_linkedin'))
                    ->toggleable(isToggledHiddenByDefault: true) // Désactivé par défaut
                    ->url(fn ($record) => $record->link_linkedin)
                    ->openUrlInNewTab()
                    ->icon('ri-linkedin-fill') // Icône pour LinkedIn
                    ->color('info')
                    ->placeholder(__('users.default')),

                TextColumn::make('link_instagram')
                    ->label(__('users.table.link_instagram'))
                    ->toggleable(isToggledHiddenByDefault: true) // Désactivé par défaut
                    ->url(fn ($record) => $record->link_instagram)
                    ->openUrlInNewTab()
                    ->icon('ri-instagram-fill') // Icône pour Instagram
                    ->color('info')
                    ->placeholder(__('users.default')),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('messages.created_at')),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('messages.updated_at')),

            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('users.table.role'))
                    ->multiple()
                    ->options(function(){
                        $typeUserFilters = [];
                        if(auth()->user()->can('manage_admin')){
                            $typeUserFilters['admin'] = __('users.role.admin');
                        }
                        if(auth()->user()->can('manage_freelance')){
                            $typeUserFilters['freelance'] = __('users.role.freelance');
                        }
                        if(auth()->user()->can('manage_users')){
                            $typeUserFilters['client'] = __('users.role.client');
                        }
                        return $typeUserFilters;
                    }),
                    //->options([ __('users.role.client'),  __('users.role.freelance'),  __('users.role.admin')]),

                SelectFilter::make('status')
                    ->label(__('users.table.status'))
                    ->multiple()
                    ->options([
                        'active' => __('users.status.active'),
                        'inactive' => __('users.status.inactive'),
                        'suspended' => __('users.status.suspended'),
                        'banished' => __('users.status.banished'),
                        'not-verified' => __('users.status.not-verified'),
                    ]),

            ])
            ->defaultSort('updated_at', 'desc')
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete())
                        ->deselectRecordsAfterCompletion(),
                ])
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('managePermissions')
                        ->label('Gérer les permissions')
                        ->icon('heroicon-o-lock-closed')
                        ->visible(fn (User $user) => !$user->hasPermissionTo('manage_permission_for_user'))
                        ->url(fn (User $record) => route('filament.admin.resources.users.manage-user-permissions', ['record' => $record->id])),
                ])->icon('heroicon-m-ellipsis-horizontal')->color('info'),
            ]);

    }


    // Requête sql pour récupérer les utilisateurs
    public static function getEloquentQuery(): Builder
    {
        /** @var User $user */
        $user = Auth::user();
        $query = parent::getEloquentQuery();
        $filters = [];
        if($user->can('manage_admin')){
            $filters[] = 'admin';
        }
        if($user->can('manage_freelance')){
            $filters[] = 'freelance';
        }
        if($user->can('manage_users')){
            $filters[] = 'client';
        }
        if(!empty($filters)){
            $query->whereIn('role', $filters);
        }
        return $query->where('id', '!=', $user->id);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewUser::class,
            Pages\EditUser::class,
            Pages\ManageUserPermissions::class,
        ]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return UserResource::getUrl('edit', ['record' => $record]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'role']; // Champs à rechercher
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /**
         * Résultat de la recherche
         * @var Post $record
         * */
        $details = [];

        if ($record->email) {
            $details['Email'] = $record->email;
        }

        if ($record->role) {
            $details['Type'] = __('users.role.' . $record->role);
        }

        return $details;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
            'manage-user-permissions' => ManageUserPermissions::route('/{record}/permissions'),

        ];
    }
}
