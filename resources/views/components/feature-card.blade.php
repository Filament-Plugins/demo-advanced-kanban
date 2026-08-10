@php
    use Filament\Support\Enums\IconSize;

    $iconStyles = match ($color) {
        'primary' => 'bg-primary-500/10 text-primary-600 dark:text-primary-400',
        'success' => 'bg-success-500/10 text-success-600 dark:text-success-400',
        'info' => 'bg-info-500/10 text-info-600 dark:text-info-400',
        'warning' => 'bg-warning-500/10 text-warning-600 dark:text-warning-400',
        'danger' => 'bg-danger-500/10 text-danger-600 dark:text-danger-400',
        'purple' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400',
        'indigo' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400',
        'teal' => 'bg-teal-500/10 text-teal-600 dark:text-teal-400',
        'cyan' => 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400',
        'rose' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
        'amber' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
        'gray' => 'bg-gray-500/10 text-gray-600 dark:text-gray-400',
        default => 'bg-primary-500/10 text-primary-600 dark:text-primary-400',
    };
@endphp

@props([
    'icon',
    'title',
    'description',
    'color' => 'primary',
])

<div class="ak-feature-card group border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-white/[0.03] hover:border-gray-300 dark:hover:border-white/20">
    <div class="ak-feature-icon {{ $iconStyles }} mb-3 transition-transform duration-200 group-hover:scale-110">
        <x-filament::icon :icon="$icon" :size="IconSize::Large" class="h-5 w-5" />
    </div>
    <h3 class="mb-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400">{{ $description }}</p>
</div>
