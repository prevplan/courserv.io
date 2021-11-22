@props([
    'href' => '#'
])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'block px-4 py-2 text-sm text-gray-700']) }} role="menuitem">{{ $slot }}</a>
