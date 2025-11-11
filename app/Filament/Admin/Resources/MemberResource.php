<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\MemberResource\Pages;
use App\Filament\Admin\Resources\MemberResource\RelationManagers;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Member Information')
                    ->schema([
                        Forms\Components\TextInput::make('created_by_name')
                            ->label('Created By')
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record && $record->createdBy) {
                                    $component->state($record->createdBy->name);
                                } else {
                                    $component->state(auth()->user()->name);
                                }
                            })
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('member_code')
                            ->label('Member Code')
                            ->default(fn() => self::generateMemberCode())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Auto-generated member code'),

                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter member name'),

                        Forms\Components\Select::make('gender')
                            ->label('Gender')
                            ->required()
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->native(false),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Birth Date')
                            ->required()
                            ->native(false)
                            ->maxDate(now())
                            ->displayFormat('d/m/Y'),

                        Forms\Components\DatePicker::make('join_date')
                            ->label('Join Date')
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->displayFormat('d/m/Y'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->rows(3)
                            ->placeholder('Enter full address'),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->required()
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('e.g., 08123456789'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->placeholder('member@example.com'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Profile Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Member Photo')
                            ->image()
                            ->imageEditor()
                            ->directory('members')
                            ->maxSize(2048)
                            ->disk('public')
                            ->visibility('public')
                            ->nullable(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function generateMemberCode(): string
    {
        $dateCode = now()->format('dmy');
        $randomNumber = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        return "M-{$dateCode}-{$randomNumber}";
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->paginated()
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                IconColumn::make('status')
                    ->boolean()
                    ->getStateUsing(fn(Member $record): bool => $record->status == 'active')
                    ->sortable(),

                TextColumn::make('join_date')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->queries(
                        true: fn(Builder $query) => $query->where('status', 'active'),
                        false: fn(Builder $query) => $query->where('status', 'inactive'),
                        blank: fn(Builder $query) => $query
                    )
                    ->native(false)
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make()
                        ->visible(
                            function ($record) {
                                if ($record->borrowings()->exists()) {
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        )
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        $allowed = $records->filter(
                            fn($record) => !$record->borrowings()->exists()
                        );

                        $blocked = $records->diff($allowed);

                        // If none can be deleted
                        if ($allowed->isEmpty()) {
                            Notification::make()
                                ->title('Action Cancelled')
                                ->body('No members can be deleted. Members with related borrowings cannot be deleted.')
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

                        // Show notification for blocked records
                        if ($blocked->isNotEmpty()) {
                            Notification::make()
                                ->title('Partial Success')
                                ->body("Successfully deleted {$deleted} member(s). Some members could not be deleted: " . $blocked->pluck('name')->join(', '))
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Success')
                                ->body("Successfully deleted {$deleted} member(s).")
                                ->success()
                                ->send();
                        }
                    }),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
            'view' => Pages\ViewMember::route('/{record}'),
        ];
    }
}
