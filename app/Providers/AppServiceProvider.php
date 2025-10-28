<?php

namespace App\Providers;

use Asmit\AdvancedKanban\RenderHooks\KanbanRenderHook;
use Filament\Support\Facades\FilamentView;
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
            fn() => view('filament.kanban.partials.search-before'),
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
