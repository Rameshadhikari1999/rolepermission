@props(['active'])

@php
$classes = ($active ?? false)
            ? 'w-fll inline-flex items-center px-5 py-2 bg-blue-700 text-md rounded font-medium leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'w-fll inline-flex items-center px-5 py-2 text-md rounded text-white font-medium leading-5 text-gray-500 hover:bg-blue-600  focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
