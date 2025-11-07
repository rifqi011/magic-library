<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Collection;
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
                    ->falseLabel('Inactive')
                    ->queries(
                        true: fn(Builder $query) => $query->where('status', 'active'),
                        false: fn(Builder $query) => $query->where('status', 'inactive'),
                        blank: fn(Builder $query) => $query
                    )
                    ->native(false),

                Tables\Filters\TernaryFilter::make('role')
                    ->trueLabel('Super Admin')
                    ->falseLabel('Admin'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->visible(function ($record) {
                            $authUser = auth()->user();

                            if ($record->id === $authUser->id) {
                                return false;
                            }

                            if ($record->role === 'superadmin') {
                                return false;
                            }

                            if ($record->members()->exists()) {
                                return false;
                            }

                            return true;
                        }),
                ])
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        $currentUser = auth()->user();

                        // Filter records that are allowed to be deleted
                        $allowed = $records->filter(
                            fn($record) =>
                            $record->id !== $currentUser->id &&
                                $record->role !== 'superadmin' &&
                                !$record->members()->exists()
                        );

                        $blocked = $records->diff($allowed);

                        if ($allowed->isEmpty()) {
                            Notification::make()
                                ->title('Action Cancelled')
                                ->body('No users can be deleted. Users with related members, super admins, or your own account cannot be deleted.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // Delete only allowed records
                        $deleted = 0;
                        foreach ($allowed as $record) {
                            $record->delete();
                            $deleted++;
                        }

                        if ($blocked->isNotEmpty()) {
                            Notification::make()
                                ->title('Partial Success')
                                ->body("Successfully deleted {$deleted} user(s). Some users could not be deleted: " . $blocked->pluck('name')->join(', '))
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Success')
                                ->body("Successfully deleted {$deleted} user(s).")
                                ->success()
                                ->send();
                        }
                    })
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
            'view' => Pages\ViewUser::route('/{record}')
        ];
    }
}
