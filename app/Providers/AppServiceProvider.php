<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Both of these land in the topbar's end slot, in registration order. The promo used to
         * hang off KANBAN_SEARCH_BEFORE, but that row is the board's own toolbar — the plugin
         * pulls it up onto the tab strip's line, and a banner that wide overlapped the tabs.
         */
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn () => view('filament.partials.promo-banner'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn () => view('livewire.social-links')
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
