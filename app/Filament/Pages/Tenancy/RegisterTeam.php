<?php
namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Forms\Components\Hidden;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\HtmlString;
class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Merchant'),
                TextInput::make('slug')
                    ->label('ID Merchant')
                    ->required()
                    ->live(onBlur: false) // Validasi setiap ketikan
                    ->afterStateUpdated(function (string $state, $set, $get) {
                        if (empty($state))
                            return;

                        // Cek keberadaan slug di database
                        $exists = \DB::table('teams')
                            ->where('slug', $state)
                            ->exists();

                        if ($exists) {
                            // Jika slug sudah ada, set error state
                            $set('slug_error', 'ID Merchant sudah digunakan!');
                        } else {
                            // Jika slug tersedia, hapus error state
                            $set('slug_error', null);
                        }
                    })
                    ->extraInputAttributes(fn(callable $get) => [
                        // Tambahkan class CSS berdasarkan status error
                        'class' => $get('slug_error') ? 'border-danger-600' : ''
                    ])
                    ->helperText(
                        fn(callable $get) =>
                        // Tampilkan pesan error jika ada
                        $get('slug_error') ?
                        new HtmlString('<span class="text-danger-600">' . $get('slug_error') . '</span>') :
                        'Masukkan ID Merchant yang unik'
                    )
                    ->dehydrateStateUsing(function (string $state) {
                        // Final validation sebelum submit
                        $exists = \DB::table('teams')
                            ->where('slug', $state)
                            ->exists();

                        if ($exists) {
                            throw ValidationException::withMessages([
                                'slug' => 'ID Merchant sudah digunakan!'
                            ]);
                        }

                        return $state;
                    }),
                TextInput::make('location'),
                // ...
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

        $team->members()->attach(auth()->user());

        return $team;
    }
}