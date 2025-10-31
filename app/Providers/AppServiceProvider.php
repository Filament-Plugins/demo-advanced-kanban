<?php

namespace App\Providers;

use Asmit\AdvancedKanban\RenderHooks\KanbanRenderHook;
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
        FilamentView::registerRenderHook(
            KanbanRenderHook::KANBAN_SEARCH_BEFORE,
            fn () => view('filament.kanban.partials.search-before'),
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
