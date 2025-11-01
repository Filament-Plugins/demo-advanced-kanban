@php
    use Filament\Support\Icons\Heroicon;

    $features = [
        [
            'icon' => Heroicon::OutlinedArrowsPointingOut,
            'title' => 'Drag & Drop',
            'description' => 'Intuitive drag-and-drop interface for moving records',
            'color' => 'primary',
        ],
        [
            'icon' => Heroicon::OutlinedArrowPath,
            'title' => 'Workflow',
            'description' => 'Define allowed status transitions to control movement',
            'color' => 'success',
        ],
        [
            'icon' => Heroicon::OutlinedMagnifyingGlass,
            'title' => 'Real-time Search',
            'description' => 'Search across multiple fields with debounced input',
            'color' => 'info',
        ],
        [
            'icon' => Heroicon::OutlinedFunnel,
            'title' => 'Advanced Filtering',
            'description' => 'Custom filter forms with multiple field types',
            'color' => 'warning',
        ],
        [
            'icon' => Heroicon::OutlinedChevronDoubleDown,
            'title' => 'Infinite Scroll',
            'description' => 'Load more records per column with infinite scroll',
            'color' => 'gray',
        ],
        [
            'icon' => Heroicon::OutlinedSquare3Stack3d,
            'title' => 'Custom Components',
            'description' => 'Rich, customizable card and column components',
            'color' => 'purple',
        ],
        [
            'icon' => Heroicon::OutlinedLockClosed,
            'title' => 'Card Locking',
            'description' => 'Prevent specific cards from being moved based on conditions',
            'color' => 'danger',
        ],
        [
            'icon' => Heroicon::OutlinedBookmark,
            'title' => 'Session Persistence',
            'description' => 'Filters and search automatically saved in session',
            'color' => 'indigo',
        ],
        [
            'icon' => Heroicon::OutlinedSquare3Stack3d,
            'title' => 'Tab Filtering',
            'description' => 'Filter records by status using intuitive tab navigation',
            'color' => 'teal',
        ],
        [
            'icon' => Heroicon::OutlinedPlusCircle,
            'title' => 'Header Actions',
            'description' => 'Add new records directly from column headers quickly',
            'color' => 'cyan',
        ],
        [
            'icon' => Heroicon::OutlinedEllipsisVertical,
            'title' => 'Record Actions',
            'description' => 'Edit, delete, and view actions on individual cards',
            'color' => 'rose',
        ],
        [
            'icon' => Heroicon::OutlinedCog6Tooth,
            'title' => 'Query Modifiers',
            'description' => 'Fine-tune search queries with advanced filtering options',
            'color' => 'amber',
        ],
    ];
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Advanced Kanban Features
        </x-slot>

        <x-slot name="description">
            Explore the powerful capabilities of Advanced Kanban for Filament 4.x
        </x-slot>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach ($features as $feature)
                <x-feature-card
                    :icon="$feature['icon']"
                    :title="$feature['title']"
                    :description="$feature['description']"
                    :color="$feature['color']"
                />
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

