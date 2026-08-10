@php
    use Asmit\AdvancedKanban\RenderHooks\KanbanRenderHook;
    use Filament\Support\Facades\FilamentView;
@endphp

{{-- Same shape as the plugin's own page view. The extra class is what the demo styling in
     resources/css/filament/admin/theme.css hangs off, so it stays off every other board. --}}
<x-filament-panels::page class="kanban-panel kanban-demo">
    {{ $this->getKanban() }}

    <x-filament-actions::modals />

    {{ FilamentView::renderHook(KanbanRenderHook::KANBAN_PAGE_FOOTER) }}
</x-filament-panels::page>
