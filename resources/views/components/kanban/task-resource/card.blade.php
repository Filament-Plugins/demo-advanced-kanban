@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\Enums\Size;
    use Filament\Support\Icons\Heroicon;
@endphp
{{-- TaskResource Kanban Card Component --}}
{{-- This component is based on the Advanced Kanban package card --}}
{{-- You can customize this component to display your model's data --}}

@props([
    'record',
    'lockedColumn',
    'actions' => []
])

@php
    // A badge on every priority makes every card shout equally, which reads the same as none of
    // them shouting. Only the priority worth interrupting for gets one.
    $isHighPriority = $record->priority === \App\Enums\Priority::HIGH;
    $isOverdue = $record->due_date && $record->due_date->isPast() && ! in_array($record->status, [\App\Enums\TaskStatus::COMPLETED, \App\Enums\TaskStatus::ARCHIVED], true);
@endphp

<div>
    <div class="kanban-item-header">
        <h4 class="kanban-item-title">
            {{ $record->{$this->getKanban()->getTitleField()} }}
        </h4>
        <div class="kanban-action flex">
            @foreach($actions as $action)
                {{ $action }}
            @endforeach
        </div>
    </div>

    <div wire:click="mountAction('viewAction', {recordId: {{ $record->id }} })">
        @if($record->{$this->getKanban()->getDescriptionField()})
        <p class="kanban-item-description">
            {{ Str::limit($record->{$this->getKanban()->getDescriptionField()}, 80) }}
        </p>
        <div class="flex items-center justify-between gap-2 mt-2 w-full">
            @if($name = $record->assignedTo?->name)
                <div class="flex items-center gap-2">
                    <x-filament::avatar
                        :size="Size::Small->value"
                        src="https://api.dicebear.com/9.x/adventurer/svg?seed={{ $name }}"
                        alt="Advanced Kanban"
                        class="ring-2 ring-white dark:ring-zinc-700"
                    />
                    <span class="text-xs">{{ $name }}</span>
                </div>
            @elseif($lockedColumn->isCardLocked)
                <div class="flex items-center gap-x-1 bg-zinc-100 dark:bg-zinc-700 px-2 py-1 rounded-full">
                    <x-filament::icon
                        :icon=" $lockedColumn->icon"
                        class="h-3 w-3 text-gray-500 dark:text-gray-200"
                    />
                    <span class="!text-xs font-medium text-gray-500 dark:text-gray-200">{{ $lockedColumn->label }}</span>
                </div>
            @else
                <span></span>
            @endif

            <div class="flex shrink-0 items-center gap-2">
                @if($isHighPriority)
                    <x-filament::badge size="sm" color="danger">
                        High
                    </x-filament::badge>
                @endif

                <div class="flex shrink-0 items-center gap-x-1">
                    <x-filament::icon
                        :icon="$isOverdue ? Heroicon::OutlinedExclamationCircle : Heroicon::Calendar"
                        :size="IconSize::Small"
                        class="{{ $isOverdue ? 'text-danger-500' : 'text-zinc-500' }}"
                    />
                    <span class="text-xs {{ $isOverdue ? 'text-danger-500 font-medium' : '' }}">{{ $record->due_date?->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
