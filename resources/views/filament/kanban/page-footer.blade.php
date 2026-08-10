@php
    $legend = [
        ['icon' => 'heroicon-o-lock-closed', 'label' => 'Locked', 'hint' => 'stays where it is'],
        ['icon' => 'heroicon-o-clipboard-document-list', 'label' => 'A modal on drop', 'hint' => 'the column wants a note first'],
        ['icon' => 'heroicon-o-arrows-up-down', 'label' => 'Drag up or down', 'hint' => 'to reorder within a column'],
    ];
@endphp

{{-- Rendered through KanbanRenderHook::KANBAN_PAGE_FOOTER. --}}
<div class="flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-gray-200 pt-4 dark:border-white/10">
    @foreach ($legend as $item)
        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
            <x-filament::icon
                :icon="$item['icon']"
                class="size-4 shrink-0 text-gray-400 dark:text-gray-500"
            />

            <span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item['label'] }}</span>
                &mdash; {{ $item['hint'] }}
            </span>
        </div>
    @endforeach
</div>
