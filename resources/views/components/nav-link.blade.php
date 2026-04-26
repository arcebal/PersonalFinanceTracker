@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[var(--brand)] text-sm font-medium leading-5 text-[var(--brand-strong)] focus:border-[var(--brand-strong)] focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[var(--text-tertiary)] hover:text-[var(--text-primary)] hover:border-[var(--accent-border-soft)] focus:text-[var(--text-primary)] focus:border-[var(--accent-border-soft)] focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
