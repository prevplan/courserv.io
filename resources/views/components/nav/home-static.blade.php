<div x-data="{ showMenu: {{ $active==='home' ? 'true' : 'false' }} }" @click.outside="showMenu = false">
    <x-nav.sidebar-static active="{{ $active==='home' ? 'true' : '' }}" fa="home" @click.prevent="showMenu = !showMenu">
        Dashboard
    </x-nav.sidebar-static>
</div>
