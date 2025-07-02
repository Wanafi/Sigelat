<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Livewire\Livewire;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\LaporanResource\Widgets\LaporanOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->topNavigation()
            ->brandName('SIGELAT')
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Yellow,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                LaporanOverview::class,
                Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->resources([
                \App\Filament\Resources\AlatResource::class,
                \App\Filament\Resources\MobilResource::class,
                \App\Filament\Resources\GelarResource::class,
                \App\Filament\Resources\RiwayatResource::class,
                // Tambahkan resource lainnya di sini
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
]);
    }
}
