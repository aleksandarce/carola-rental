@props([
    'route',
    'column',
    'label',
    'currentSort',
    'currentDirection' => 'asc',
    'align' => 'left',
])

@php
    $isActive = $currentSort === $column;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';

    // Dropping 'page' so changing the sort lands on page 1 of the newly
    // sorted results, not whatever page number happened to be active.
    $query = [
        ...request()->except(['sort', 'direction', 'page']),
        'sort' => $column,
        'direction' => $nextDirection,
    ];
@endphp

<th class="px-3 py-3 text-{{ $align }}">
    <a
        href="{{ route($route, $query) }}"
        class="inline-flex items-center gap-1 text-theme-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
    >
        {{ $label }}
        <svg
            class="size-3 shrink-0 fill-current transition-transform duration-150 {{ $isActive ? '' : 'invisible' }} {{ $isActive && $currentDirection === 'desc' ? 'rotate-180' : '' }}"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" />
        </svg>
    </a>
</th>
