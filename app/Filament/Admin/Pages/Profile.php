<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use App\Models\User;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $title = 'My Profile';
    protected static string $view = 'filament.admin.pages.profile';
    protected static ?string $navigationGroup = 'User Management';

    // Public properties untuk form fields
    public $avatar = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $current_password = null;
    public ?string $new_password = null;
    public ?string $new_password_confirmation = null;

    public function mount(): void
    {
        $user = Auth::user();

        // Fill form
        $this->form->fill([
            'avatar' => $user->avatar,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('avatar')
                ->label('Avatar')
                ->image()
                ->avatar()
                ->directory('avatars')
                ->disk('public')
                ->visibility('public')
                ->imagePreviewHeight('150')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                ->nullable()
                ->columnSpanFull(),

            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->rules([
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    $exists = User::where('email', $value)
                                        ->where('id', '!=', Auth::id())
                                        ->exists();

                                    if ($exists) {
                                        $fail('The email has already been taken.');
                                    }
                                };
                            },
                        ]),
                ]),

            Forms\Components\Section::make('Change Password')
                ->description('Leave all password fields blank if you don\'t want to change your password')
                ->schema([
                    Forms\Components\TextInput::make('current_password')
                        ->label('Current Password')
                        ->password()
                        ->revealable()
                        ->autocomplete('current-password')
                        ->nullable()
                        ->rules([
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    if ($this->new_password && !$value) {
                                        $fail('Current password is required when setting a new password.');
                                    }

                                    if ($value && !Hash::check($value, Auth::user()->password)) {
                                        $fail('The current password is incorrect.');
                                    }
                                };
                            },
                        ]),

                    Forms\Components\TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->minLength(8)
                        ->maxLength(255)
                        ->autocomplete('new-password')
                        ->nullable()
                        ->same('new_password_confirmation')
                        ->rules([
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    if ($value && !$this->current_password) {
                                        $fail('Please enter your current password.');
                                    }
                                };
                            },
                        ]),

                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->label('Confirm New Password')
                        ->password()
                        ->revealable()
                        ->nullable()
                        ->dehydrated(false),
                ])
                ->columns(1)
                ->collapsible(),
        ];
    }

    public function submit(): void
    {
        // Validasi form
        $data = $this->form->getState();

        /** @var User $user */
        $user = Auth::user();

        // Simpan avatar lama untuk penghapusan nanti
        $oldAvatar = $user->avatar;

        // Update name & email
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar']) {
            // Hapus avatar lama jika ada dan berbeda
            if ($oldAvatar && $oldAvatar !== $data['avatar'] && Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
            // Set avatar baru
            $user->avatar = $data['avatar'];
        } elseif (!isset($data['avatar']) && $oldAvatar) {
            // Avatar dihapus
            if (Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
            $user->avatar = null;
        }

        // Handle password change
        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        // Save user
        $user->save();

        // Reset password fields
        $this->current_password = null;
        $this->new_password = null;
        $this->new_password_confirmation = null;

        // Show success notification
        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();

        // Refresh form dengan data terbaru
        $this->form->fill([
            'avatar' => $user->fresh()->avatar,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
