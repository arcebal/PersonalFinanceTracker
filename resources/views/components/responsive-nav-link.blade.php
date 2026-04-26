@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-[var(--brand)] bg-[var(--brand-soft)] py-2 ps-3 pe-4 text-start text-base font-medium text-[var(--brand-strong)] transition duration-150 ease-in-out focus:border-[var(--brand-strong)] focus:bg-[var(--brand-soft)] focus:text-[var(--brand-strong)] focus:outline-none'
            : 'block w-full border-l-4 border-transparent py-2 ps-3 pe-4 text-start text-base font-medium text-[var(--text-secondary)] transition duration-150 ease-in-out hover:border-[var(--border)] hover:bg-[var(--bg-panel-soft)] hover:text-[var(--text-primary)] focus:border-[var(--border)] focus:bg-[var(--bg-panel-soft)] focus:text-[var(--text-primary)] focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
