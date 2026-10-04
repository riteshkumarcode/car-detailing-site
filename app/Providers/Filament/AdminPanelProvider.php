<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('The Drive Clinic')
            ->brandLogo(fn () => view('components.logo', ['variant' => 'dark', 'height' => 36]))
            ->darkMode(false)
            ->font('Barlow')
            ->colors([
                'primary' => [
                    50  => '#fef7ec',
                    100 => '#fdecd3',
                    200 => '#fbd7a5',
                    300 => '#f7be6e',
                    400 => '#F2A93B',
                    500 => '#e08f23',
                    600 => '#c47119',
                    700 => '#9f5317',
                    800 => '#81431a',
                    900 => '#6b381a',
                    950 => '#3d1c0b',
                ],
                'teal' => [
                    50  => '#edf6f4',
                    100 => '#d5eae5',
                    200 => '#b0d6ce',
                    300 => '#81bcb1',
                    400 => '#549f93',
                    500 => '#1F5E57',
                    600 => '#163F3A',
                    700 => '#1a554f',
                    800 => '#184440',
                    900 => '#163a37',
                    950 => '#0b211f',
                ],
                'danger' => [
                    50  => '#fbf3f2',
                    100 => '#f6e0db',
                    200 => '#ecc3bb',
                    300 => '#dc9e93',
                    400 => '#c87364',
                    500 => '#A63A2B',
                    600 => '#95382b',
                    700 => '#7d2e23',
                    800 => '#682820',
                    900 => '#58251e',
                    950 => '#30100c',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ]);

        $adminDomain = config('domains.admin_domain');
        if ($adminDomain && !app()->environment(['local', 'testing'])) {
            $panel->domain($adminDomain)->path('');
        }

        return $panel;
    }
}
