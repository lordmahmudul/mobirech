@props(['active' => false])

@php
$classes = $active
    ? 'flex items-center px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-md transition'
    : 'flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
