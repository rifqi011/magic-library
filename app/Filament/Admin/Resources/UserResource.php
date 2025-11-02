<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Admin';
    protected static ?string $pluralModelLabel = 'Admins';
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Admins';

    public static function canViewAny(): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user->isSuperAdmin() && $user->id !== $record->id;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar')
                    ->label('Profile Picture')
                    ->directory('avatars')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\Select::make('role')
                    ->options([
                        'superadmin' => 'Super Admin',
                        'admin' => 'Admin',
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required()
                    ->disabled(fn($record) => $record && $record->id === auth()->id() && $record->role === 'superadmin'),

                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn(string $context): bool => $context === 'create')
                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn($state) => filled($state)) // ⬅️ tambahkan baris ini
                        ->same('password_confirmation')
                        ->revealable()
                        ->helperText('Leave blank if you do not want to change it.'),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->required(fn(callable $get) => filled($get('password')))
                        ->revealable()
                        ->dehydrated(false),
                ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'primary',
                        'success' => 'superadmin',
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->getStateUsing(fn(User $record): bool => $record->status == 'active')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),

                Tables\Filters\TernaryFilter::make('role')
                    ->trueLabel('Super Admin')
                    ->falseLabel('Admin'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => $record->id !== auth()->id()),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
