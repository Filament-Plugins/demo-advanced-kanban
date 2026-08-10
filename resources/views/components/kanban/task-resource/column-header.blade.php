{{-- This component is based on the Advanced Kanban package column header --}}
{{-- You can customize this component to match your design requirements --}}

@php
    use Filament\Support\Enums\IconSize;
@endphp
@props([
    'column' => null,
    'status' => null,
    'actions' => [],
])
<div>
    <div class="flex items-start justify-between gap-2">
        <div class="kanban-column-label min-w-0">
            <div class="min-w-0 space-y-1">
                <div class="flex items-center gap-2">
                    {{-- Bare, not on a tile: a tile next to the real buttons on the other end of
                         this row reads as a third button. --}}
                    <x-filament::icon :icon="$column->getIcon()"
                                      color="{{ $column->getIconColor() }}"
                                      :size="IconSize::Small"
                                      class="kanban-column-icon shrink-0"
                    />

                    <h3 class="kanban-column-title truncate uppercase">
                        {{ $column->getLabel() ?? $column->getStatus() }}
                    </h3>

                    <span class="kanban-column-count shrink-0">
                        {{ $this->getKanban()->getTotalCount($column->getStatus()) ?? 0 }}
                    </span>
                </div>

                @if ($description = $column->getDescription())
                    <p class="kanban-column-description text-xs text-gray-500">
                        {{ $description }}
                    </p>
                @endif
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-1">
            @foreach($actions as $action)
                {{ $action }}
            @endforeach
        </div>
    </div>
</div>
