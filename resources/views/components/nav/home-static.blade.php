<div x-data="{ showMenu: {{ $active==='home' ? 'true' : 'false' }} }" @click.outside="showMenu = false">
    <x-nav.sidebar-static active="{{ $active==='home' ? 'true' : '' }}" href="{{ route('home') }}" fa="home">
        Dashboard
    </x-nav.sidebar-static>
</div>
