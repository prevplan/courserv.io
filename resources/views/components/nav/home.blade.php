<div x-data="{ showMenu: {{ $active==='home' ? 'true' : 'false' }} }" @click.outside="showMenu = false">
    <x-nav.sidebar active="{{ $active==='home' ? 'true' : '' }}" fa="home" href="{{ route('home') }}">
        Dashboard
    </x-nav.sidebar>
</div>
