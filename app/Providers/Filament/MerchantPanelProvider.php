<?php

namespace App\Providers\Filament;

use App\Filament\Merchant\Auth\MerchantRegistration;
use App\Filament\Pages\Tenancy\EditTeamProfile;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Http\Middleware\CheckIfUserIsActive;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
                // if(Auth::check()) && Auth::user()->can('')) {
                //    ; 
                // },
                 
                EncryptCookies::class,
                
                AddQueuedCookiesToResponse::class,
                
                StartSession::class,
                
                AuthenticateSession::class,
                
                ShareErrorsFromSession::class,
                
                VerifyCsrfToken::class,
                
                SubstituteBindings::class,
                // CheckIfUserIsActive::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                
            ])
            ->authMiddleware([
                
                Authenticate::class,
                CheckIfUserIsActive::class,
            ])
            ->tenantMiddleware([
                
            ], isPersistent: true)
            ->tenant(Team::class, )
            ->tenantRegistration(RegisterTeam::class)
            ->tenantProfile(EditTeamProfile::class)
            ->tenantMenuItems([
                    // Hanya sertakan item menu yang Anda inginkan
                Pages\TenantProfile::class,
            ])
            ->tenantMenu(false);
    }
}
