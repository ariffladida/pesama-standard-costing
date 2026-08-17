<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(fn () => new HtmlString('
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 font-bold text-xs shrink-0">
                        ST
                    </div>
                    <div class="flex flex-col text-left fi-logo-text">
                        <span class="font-bold text-sm tracking-wide text-slate-800 dark:text-slate-100 whitespace-nowrap">Pesama Standard Costing</span>
                        <span class="text-[10px] text-slate-400 whitespace-nowrap">Pesama Timber</span>
                    </div>
                </div>
            '))
            // Suntik CSS agar logo ST kekal dipaparkan walaupun sidebar collapsed
            ->renderHook(
                'panels::head.done',
                fn () => new HtmlString('
                    <style>
                        /* Benarkan bahagian brand logo kekal nampak bila sidebar ditutup */
                        .fi-sidebar-closeable .fi-sidebar-header {
                            display: flex !important;
                            align-items: center;
                            justify-content: space-between;
                        }
                        /* Sembunyikan teks nama bila collapsed, tetapi kekalkan kotak lencana ST */
                        aside.fi-sidebar:not(.fi-sidebar-open) .fi-logo-text {
                            display: none !important;
                        }
                    </style>
                ')
            )
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->font('Plus Jakarta Sans')
            ->navigationGroups([
                'Standard Costing',
                'Master Data',
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
    }
}