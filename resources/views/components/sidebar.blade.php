@props([
    'active' => '',
    'href' => '#',
    'fa' => 'bug'
])
{{-- Current: "bg-gray-100 text-gray-900", Default: "text-gray-600 hover:bg-gray-50 hover:text-gray-900" --}}
<a href="{{ $href }}" class="{{ $active ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-base font-medium rounded-md">
    {{--
      Heroicon name: outline/home

      Current: "text-gray-500", Default: "text-gray-400 group-hover:text-gray-500"
    --}}
    <i class="fas fa-{{ $fa }} {{ $active ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} mr-4 flex-shrink-0 w-6"></i>
    {{ $slot }}
</a>
