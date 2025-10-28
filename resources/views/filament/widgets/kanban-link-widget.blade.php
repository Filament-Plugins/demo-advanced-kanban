@php use App\Filament\Pages\KanbanTask;use Filament\Support\Icons\Heroicon; @endphp
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex gap-4">
            <x-filament::button color="primary"
                                size="lg"
                                :icon="Heroicon::OutlinedRocketLaunch"
                                :href="KanbanTask::getUrl()" tag="a" class="w-full">
                Try Kanaban Demo Now
            </x-filament::button>
            <x-filament::button color="primary"
                                size="lg"
                                :icon="Heroicon::OutlinedCreditCard"
                                href="https://checkout.anystack.sh/filament-advanced-kanban"
                                target="_blank"
                                tag="a" class="w-full">
                Buy Advanced Kanban Now
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
