<?php

namespace App\Filament\Merchant\Auth;

use Filament\Pages\Auth\Register;
use App\Models\User;
use App\Models\Team;
use Spatie\Permission\Models\Role;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Forms;

class MerchantRegistration extends Register
{
    protected function getFormSchema(): array
    {
        return [
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
            Forms\Components\TextInput::make('team_name')
                ->label('Team Name')
                ->required(),
            Forms\Components\TextInput::make('team_slug')
                ->label('Team Slug')
                ->required()
                ->unique('teams', 'slug'),
            Forms\Components\TextInput::make('team_location')
                ->label('Team Location')
                ->required(),
        ];
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();

        $user = DB::transaction(function () use ($data) {
            $user = $this->getUserModel()::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $merchantRole = Role::findOrCreate('merchant');
            $user->assignRole($merchantRole);

            // $team = Team::create([
            //     'name' => $data['team_name'],
            //     'slug' => $data['team_slug'],
            //     'location' => $data['team_location'],
            // ]);
            // $user->teams()->attach($team->id);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}