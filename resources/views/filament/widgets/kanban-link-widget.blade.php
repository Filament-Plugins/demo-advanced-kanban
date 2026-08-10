@php use App\Filament\Pages\KanbanTask;use Filament\Support\Icons\Heroicon; @endphp

<x-filament-widgets::widget>
    <div class="ak-hero">
        <div class="ak-hero-glow ak-hero-glow-a"></div>
        <div class="ak-hero-glow ak-hero-glow-b"></div>
        <div class="ak-hero-grid"></div>

        <div class="relative flex flex-col gap-6 p-6 sm:p-10 lg:p-12">
            <div class="inline-flex w-fit items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 backdrop-blur-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                <span class="text-xs font-medium tracking-wide text-white/90">v2.0 &middot; Built for Filament 5</span>
            </div>

            <div class="max-w-3xl space-y-3">
                <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Advanced Kanban <span class="text-amber-400">for Filament</span>
                </h1>
                <p class="text-base leading-relaxed text-white/70 sm:text-lg">
                    A production-grade drag-and-drop board builder &mdash; workflow transitions, real-time search,
                    advanced filtering, and fully customizable cards, wired straight into your Eloquent models.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <x-filament::button
                    color="primary"
                    size="lg"
                    :icon="Heroicon::OutlinedRocketLaunch"
                    :href="KanbanTask::getUrl()"
                    tag="a"
                    class="ak-hero-cta-primary"
                >
                    Launch Live Demo
                </x-filament::button>

                <x-filament::button
                    color="gray"
                    size="lg"
                    :icon="Heroicon::OutlinedCreditCard"
                    href="https://checkout.anystack.sh/filament-advanced-kanban"
                    target="_blank"
                    tag="a"
                    class="ak-hero-cta-secondary"
                >
                    Get Advanced Kanban
                </x-filament::button>
            </div>

            <div class="mt-2 grid grid-cols-2 gap-4 border-t border-white/10 pt-6 sm:grid-cols-4">
                <div>
                    <div class="text-2xl font-bold text-white">16+</div>
                    <div class="text-xs text-white/60">Core Features</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">v5</div>
                    <div class="text-xs text-white/60">Filament Ready</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">0ms</div>
                    <div class="text-xs text-white/60">Config Drag &amp; Drop</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">100%</div>
                    <div class="text-xs text-white/60">Eloquent Native</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
