<x-nav.sidebar active="{{ $active==='teams' ? 'true' : '' }}" fa="users" href="{{ route('teams') }}">
    Teams
</x-nav.sidebar>

{{--

<div x-data="{ showMenu: {{ $active==='teams' ? 'true' : 'false' }} }" @click.outside="showMenu = false">
    <x-nav.sidebar active="{{ $active==='teams' ? 'true' : '' }}" fa="users" @click.prevent="showMenu = !showMenu">
        Teams
    </x-nav.sidebar>

    <div x-show="showMenu" style="display: none;">
        <x-nav.sidebar-sub>
            {{ _i('create') }}
        </x-nav.sidebar-sub>
    </div>
</div>


--}}
