<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->disabled(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('properties.list.status'))
                    ->formatStateUsing(fn ($state) => __('properties.status.'.$state)) // Traduction
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inactive' => 'gray',
                        'active' => 'success',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'inactive' => 'heroicon-o-document',
                        'approved' => 'heroicon-o-check-circle',
                        'active' => 'heroicon-o-bolt',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
                    Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->url(fn ($record) => UserResource::getUrl('edit', ['record' => $record->user_id]))
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->url(fn ($record) => CategoryResource::getUrl('edit', ['record' => $record->category->slug]))
                    ->color('primary')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function route(): string
    {
        return 'admin/services/{slug}/edit'; // Remplace l'ID par le slug dans l'URL
    }

    // Méthode pour récupérer un service via son slug
    public static function getModelBySlug($slug)
    {
        return Service::where('slug', $slug)->firstOrFail();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
