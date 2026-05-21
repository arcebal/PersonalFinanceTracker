@props([
    'count' => 0,
    'type' => 'info',
    'hidden' => true,
    'key' => null,
])

@php
    $fallback = [
        'info' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'warning' => 'bg-amber-50 text-amber-600 ring-amber-500/20',
        'danger' => 'bg-red-50 text-red-600 ring-red-500/20',
        'success' => 'bg-emerald-50 text-emerald-600 ring-emerald-500/20',
        'neutral' => 'bg-gray-100 text-gray-600 ring-gray-500/20',
    ];
    $fallbackStyle = $fallback[$type] ?? $fallback['info'];
@endphp

<span x-data="{
    count: {{ $count }},
    hidden: {{ $hidden ? 'true' : 'false' }},
    badgeType: '{{ $type }}'
}" x-init="window.addEventListener('badge-update', (e) => {
    if (e.detail && e.detail['{{ $key }}'] !== undefined) {
        const b = e.detail['{{ $key }}'];
        count = b.count;
        hidden = b.hidden;
        badgeType = b.type;
    }
});" x-show="!hidden" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-75"
    class="ml-auto inline-flex shrink-0 items-center justify-center min-w-[1.5rem] h-6 px-2 text-xs font-bold leading-none rounded-full ring-1 {{ $fallbackStyle }}"
    :class="{
        'bg-slate-100 text-slate-600 ring-slate-500/20': badgeType === 'info',
        'bg-amber-50 text-amber-600 ring-amber-500/20': badgeType === 'warning',
        'bg-red-50 text-red-600 ring-red-500/20': badgeType === 'danger',
        'bg-emerald-50 text-emerald-600 ring-emerald-500/20': badgeType === 'success',
        'bg-gray-100 text-gray-600 ring-gray-500/20': badgeType === 'neutral',
    }">
    <span x-text="count > 99 ? '99+' : count"></span>
</span>
