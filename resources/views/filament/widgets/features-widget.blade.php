@php
    use Filament\Support\Icons\Heroicon;

    $newInV2 = [
        [
            'icon' => Heroicon::OutlinedDocumentDuplicate,
            'title' => 'Replicate Action',
            'description' => 'Duplicate a card into the same column in one click',
        ],
        [
            'icon' => Heroicon::OutlinedArrowUp,
            'title' => 'Move to Top',
            'description' => 'Send a card to the front of its column instantly',
        ],
        [
            'icon' => Heroicon::OutlinedChatBubbleBottomCenterText,
            'title' => 'Transition Modals',
            'description' => 'Collect data in a modal before a card enters a column',
        ],
        [
            'icon' => Heroicon::OutlinedCubeTransparent,
            'title' => 'Embeddable Boards',
            'description' => 'Render a board anywhere with a single view component',
        ],
    ];

    $features = [
        [
            'icon' => Heroicon::OutlinedArrowsPointingOut,
            'title' => 'Drag & Drop',
            'description' => 'Intuitive drag-and-drop interface for moving records',
        ],
        [
            'icon' => Heroicon::OutlinedArrowPath,
            'title' => 'Workflow',
            'description' => 'Define allowed status transitions to control movement',
        ],
        [
            'icon' => Heroicon::OutlinedMagnifyingGlass,
            'title' => 'Real-time Search',
            'description' => 'Search across multiple fields with debounced input',
        ],
        [
            'icon' => Heroicon::OutlinedFunnel,
            'title' => 'Advanced Filtering',
            'description' => 'Custom filter forms with multiple field types',
        ],
        [
            'icon' => Heroicon::OutlinedChevronDoubleDown,
            'title' => 'Infinite Scroll',
            'description' => 'Load more records per column with infinite scroll',
        ],
        [
            'icon' => Heroicon::OutlinedSquare3Stack3d,
            'title' => 'Custom Components',
            'description' => 'Rich, customizable card and column components',
        ],
        [
            'icon' => Heroicon::OutlinedLockClosed,
            'title' => 'Card Locking',
            'description' => 'Prevent specific cards from being moved based on conditions',
        ],
        [
            'icon' => Heroicon::OutlinedBookmark,
            'title' => 'Session Persistence',
            'description' => 'Filters and search automatically saved in session',
        ],
        [
            'icon' => Heroicon::OutlinedSquare3Stack3d,
            'title' => 'Tab Filtering',
            'description' => 'Filter records by status using intuitive tab navigation',
        ],
        [
            'icon' => Heroicon::OutlinedPlusCircle,
            'title' => 'Header Actions',
            'description' => 'Add new records directly from column headers quickly',
        ],
        [
            'icon' => Heroicon::OutlinedEllipsisVertical,
            'title' => 'Record Actions',
            'description' => 'Edit, delete, and view actions on individual cards',
        ],
        [
            'icon' => Heroicon::OutlinedCog6Tooth,
            'title' => 'Query Modifiers',
            'description' => 'Fine-tune search queries with advanced filtering options',
        ],
    ];
@endphp

<x-filament-widgets::widget>
    <div class="mb-4 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">New in v2</h2>
        <x-filament::badge color="primary" size="sm">Try it on the board</x-filament::badge>
    </div>

    <div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach ($newInV2 as $feature)
            <x-feature-card
                :icon="$feature['icon']"
                :title="$feature['title']"
                :description="$feature['description']"
                color="primary"
            />
        @endforeach
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Everything you need, out of the box</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Every card below is a live feature &mdash; try it on the board.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
        @foreach ($features as $feature)
            <x-feature-card
                :icon="$feature['icon']"
                :title="$feature['title']"
                :description="$feature['description']"
                color="gray"
            />
        @endforeach
    </div>
</x-filament-widgets::widget>

