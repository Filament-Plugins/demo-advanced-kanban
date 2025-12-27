@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\Enums\Size;
    use Filament\Support\Icons\Heroicon;
    use Illuminate\Support\Facades\Storage;
@endphp

@props([
    'record',
    'lockedColumn',
    'actions' => []
])

<div class="group relative flex flex-col gap-y-2 rounded-xl bg-white p-2 transition-all dark:bg-zinc-900 dark:ring-white/10">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-x-2">
        <h4 class="text-sm font-semibold leading-tight text-zinc-950 dark:text-white">
            {{ $record->{$this->getKanban()->getTitleField()} }}
        </h4>

        @if(count($actions))
            <div class="flex shrink-0 items-center -mr-2 -mt-2">
                @foreach($actions as $action)
                    {{ $action }}
                @endforeach
            </div>
        @endif
    </div>

    {{-- Body --}}
    <div wire:click="mountAction('viewAction', {recordId: {{ $record->id }} })" class="cursor-pointer">
        @if($description = $record->{$this->getKanban()->getDescriptionField()})
            <p class="mt-1 text-xs text-zinc-500 line-clamp-2 dark:text-zinc-400">
                {{ Str::limit($description, 80) }}
            </p>
        @endif

        @if($record->tags->count())
            <div class="mt-3 flex flex-wrap gap-1">
                @foreach($record->tags as $tag)
                    <x-filament::badge
                        size="xs"
                        color="gray"
                        class="px-1.5 py-0.5"
                    >
                        {{ $tag->name }}
                    </x-filament::badge>
                @endforeach
            </div>
        @endif

        {{-- Attachment Thumbnail --}}
        @if($record->attachments->count())
            @php
                $firstAttachment = $record->attachments->first();
                $extension = pathinfo($firstAttachment->file_path, PATHINFO_EXTENSION);
            @endphp
            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                <div class="mt-3 overflow-hidden rounded-lg ring-1 ring-zinc-950/5 dark:ring-white/10">
                    <img
                        src="{{ Storage::url($firstAttachment->file_path) }}"
                        alt="Attachment"
                        class="h-32 w-full object-cover"
                    />
                </div>
            @endif
        @endif

        {{-- Meta Info --}}
        <div class="mt-4 flex items-center justify-between gap-3">
            @if($record->due_date)
                <div class="flex items-center gap-x-1.5 text-zinc-500 dark:text-zinc-400">
                    <x-filament::icon
                        :icon="Heroicon::Calendar"
                        class="h-3.5 w-3.5"
                    />
                    <span class="text-xs font-medium">{{ $record->due_date->format('M d') }}</span>
                </div>
            @endif

            <x-filament::badge
                size="sm"
                :color="$record->priority->getColor()"
                :icon="$record->priority->getIcon()"
            >
                {{ $record->priority->getLabel() }}
            </x-filament::badge>
        </div>

        {{-- Footer --}}
        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 dark:border-white/5">
            <div class="flex items-center gap-2">
                @if($name = $record->assignedTo?->name)
                    <x-filament::avatar
                        :size="Size::Small->value"
                        src="https://api.dicebear.com/9.x/adventurer/svg?seed={{ $name }}"
                        alt="{{ $name }}"
                        class="h-6 w-6 ring-1 ring-white dark:ring-zinc-900"
                    />
                    <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">
                        {{ $name }}
                    </span>
                @else
                    @if($lockedColumn->isCardLocked)
                        <div class="flex items-center gap-x-1.5 rounded-full bg-zinc-50 px-2 py-1 dark:bg-white/5">
                            <x-filament::icon
                                :icon="$lockedColumn->icon"
                                class="h-3 w-3 text-zinc-400"
                            />
                            <span class="text-[10px] font-medium text-zinc-500 dark:text-zinc-400">
                                {{ $lockedColumn->label }}
                            </span>
                        </div>
                    @endif
                @endif
            </div>

            @if($record->attachments->count())
                <div class="flex items-center gap-x-1 text-zinc-400 transition-colors hover:text-zinc-500 dark:text-zinc-500 dark:hover:text-zinc-400">
                    <x-filament::icon
                        :icon="Heroicon::PaperClip"
                        class="h-3.5 w-3.5"
                    />
                    <span class="text-xs">{{ $record->attachments->count() }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
