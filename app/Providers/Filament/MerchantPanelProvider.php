<?php

namespace App\Providers\Filament;

use App\Filament\Merchant\Auth\MerchantRegistration;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Models\Team;
use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Hash;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Filament\Support\Colors;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Str;

class MerchantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('merchant')
            ->path('merchant')
            ->brandLogo(asset('images/home/logo_bajo_light.png'))
            ->darkModeBrandLogo(asset('images/home/logo_bajo_dark.png'))
            ->brandLogoHeight('3rem')
            ->favIcon(asset('images/home/favicon.png'))
            ->brandName('Merchant')
            ->login()
            ->registration(MerchantRegistration::class)
            ->emailVerification()
            ->passwordReset()
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Merchant/Resources'), for: 'App\\Filament\\Merchant\\Resources')
            ->discoverPages(in: app_path('Filament/Merchant/Pages'), for: 'App\\Filament\\Merchant\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Merchant/Widgets'), for: 'App\\Filament\\Merchant\\Widgets')
            ->widgets([

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(Team::class, )
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->tenantMenuItems([
                    // Hanya sertakan item menu yang Anda inginkan
                Pages\TenantProfile::class,
            ])
            ->tenantMenu(false)
            ->plugin(
                FilamentSocialitePlugin::make()
                    // (required) Add providers corresponding with providers in `config/services.php`. 
                    ->providers([
                        // Create a provider 'gitlab' corresponding to the Socialite driver with the same name.
                        Provider::make('google')
                            ->label('Google')
                            ->icon('fab-google')
                            ->color(Color::hex('#2f2a6b'))
                            ->outlined(false)
                            ->stateless(false)
                            ->scopes([
                                'openid',
                                'https://www.googleapis.com/auth/userinfo.profile',
                                'https://www.googleapis.com/auth/userinfo.email'
                            ])
                            ->with(['...']),
                    ])
                    // (optional) Override the panel slug to be used in the oauth routes. Defaults to the panel ID.
                    ->slug('admin')
                    // (optional) Enable/disable registration of new (socialite-) users.
                    ->registration(true)
                    // (optional) Enable/disable registration of new (socialite-) users using a callback.
                    // In this example, a login flow can only continue if there exists a user (Authenticatable) already.
                    // ->registration(fn(string $provider, SocialiteUserContract $oauthUser, ?Authenticatable $user) => (bool) $user)
                    // // (optional) Change the associated model class.
                    ->userModelClass(\App\Models\User::class)
                    // (optional) Change the associated socialite class (see below).
                    ->socialiteUserModelClass(\App\Models\SocialiteUser::class)
                    ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                        // Cek apakah email sudah ada
                        $existingUser = User::where('email', $oauthUser->getEmail())->first();

                        if ($existingUser) {
                            // Jika user dengan email sudah ada, kembalikan user tersebut
                            return $existingUser;
                        }

                        // Buat user baru jika belum terdaftar
                        $user = User::create([
                            'name' => $oauthUser->getName() ?? $oauthUser->getNickname(),
                            'email' => $oauthUser->getEmail(),
                            // Generate random password untuk keamanan
                            'password' => Hash::make(Str::random(16)),
                        ]);
                        $user->assignRole('merchant');
                        // Kirim notifikasi welcome (opsional)
                        // $user->notify(new WelcomeNewUserNotification());
            
                        return $user;
                    })
                    ->resolveUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                        // Logic untuk menemukan user yang sudah ada
                        return User::where('email', $oauthUser->getEmail())->first();
                    })
            );
    }
}
