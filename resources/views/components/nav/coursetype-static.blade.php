<x-nav.sidebar-static active="{{ $active==='coursetype' ? 'true' : '' }}" fa="chalkboard-teacher" href="{{ route('coursetype') }}">
    {{ _i('Course types') }}
</x-nav.sidebar-static>