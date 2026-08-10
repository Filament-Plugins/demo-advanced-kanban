<div
    class="flex items-center gap-x-3 rounded-lg border border-primary-400 bg-primary-50 py-1 pe-1 ps-3 text-sm text-primary-700 shadow-xs dark:border-primary-900 dark:bg-primary-950 dark:text-white"
    role="status"
>
    {{-- The topbar is shared with the sidebar toggle, search, notifications and the user menu, so the
         pitch is the first thing to go when the row gets tight. The button never is. --}}
    <span class="hidden min-w-0 truncate lg:inline">
        <strong>Make it yours today!</strong> Ready to supercharge your Filament?
    </span>

    <x-filament::button
        color="primary"
        class="shrink-0 dark:text-white"
        size="sm"
        shadow="sm"
        href="https://asmit-nepali.privato.pub/portal/filament-advanced-kanban/checkout"
        tag="a"
        target="_blank"
    >
        Get It Now
    </x-filament::button>
</div>
