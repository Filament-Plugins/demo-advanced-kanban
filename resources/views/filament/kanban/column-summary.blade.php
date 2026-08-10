@props(['summary'])

<div class="grid gap-4">
    <div class="grid grid-cols-3 gap-3">
        @foreach ([
            ['label' => 'Cards', 'value' => $summary['total'], 'color' => 'text-gray-950 dark:text-white'],
            ['label' => 'Overdue', 'value' => $summary['overdue'], 'color' => 'text-danger-600 dark:text-danger-400'],
            ['label' => 'Unassigned', 'value' => $summary['unassigned'], 'color' => 'text-gray-950 dark:text-white'],
        ] as $stat)
            <div class="rounded-lg bg-gray-50 p-3 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <div class="text-2xl font-semibold tabular-nums {{ $stat['color'] }}">
                    {{ $stat['value'] }}
                </div>
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                    {{ $stat['label'] }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-2">
        <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
            By priority
        </div>

        @foreach ($summary['priorities'] as $priority => $count)
            @php
                $priorityCase = \App\Enums\Priority::from($priority);
                $share = $summary['total'] > 0 ? round(($count / $summary['total']) * 100) : 0;
                $color = $priorityCase->getColor();
            @endphp

            <div class="flex items-center gap-3">
                <x-filament::badge :color="$color" size="sm" class="w-20 shrink-0">
                    {{ $priorityCase->getLabel() }}
                </x-filament::badge>

                {{-- Filament exposes each palette as CSS custom properties, so the bar can take a
                     colour the class scanner would never see. --}}
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                    <div
                        class="h-full rounded-full"
                        style="width: {{ $share }}%; background-color: var(--{{ $color }}-500)"
                    ></div>
                </div>

                <span class="w-6 shrink-0 text-right text-sm tabular-nums text-gray-500 dark:text-gray-400">
                    {{ $count }}
                </span>
            </div>
        @endforeach
    </div>
</div>
