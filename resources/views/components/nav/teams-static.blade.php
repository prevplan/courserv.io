<div x-data="{ showMenu: {{ $active==='teams' ? 'true' : 'false' }} }" @click.outside="showMenu = false">
    <x-nav.sidebar-static active="{{ $active==='teams' ? 'true' : '' }}" fa="users" @click.prevent="showMenu = !showMenu">
        Teams
    </x-nav.sidebar-static>

    <div x-show="showMenu" style="display: none;">
        <x-nav.sidebar-sub>
            {{ _i('create') }}
        </x-nav.sidebar-sub>
    </div>
</div>
