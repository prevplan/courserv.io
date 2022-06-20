@props([
    'metaTitle' => '',
    'siteTitle',
    'active' => '',
])

<x-layouts.base :metaTitle="$metaTitle">

    <div class="min-h-full">
        <nav x-data="{ open: false }" class="bg-white shadow">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex h-16 justify-between">
                    <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                        <!-- Mobile menu button -->
                        <button type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" @click="open = !open" aria-expanded="false" x-bind:aria-expanded="open.toString()">
                            <span class="sr-only">Open main menu</span>
                            <svg x-description="Icon when menu is closed.

Heroicon name: outline/menu" x-state:on="Menu open" x-state:off="Menu closed" class="block h-6 w-6" :class="{ 'hidden': open, 'block': !(open) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg x-description="Icon when menu is open.

Heroicon name: outline/x" x-state:on="Menu open" x-state:off="Menu closed" class="hidden h-6 w-6" :class="{ 'block': open, 'hidden': !(open) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                        <div class="flex flex-shrink-0 items-center">
                            <img class="block h-8 w-auto lg:hidden" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
                            <img class="hidden h-8 w-auto lg:block" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-800-text.svg" alt="Workflow">
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <!-- Current: "border-indigo-500 text-gray-900", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->

                            <x-nav.home-static :active="$active" />

                            @can('viewAny', \App\Models\Course::class)
                                <x-nav.course-static :active="$active" />
                            @endcan

                            @can('viewAny', \App\Models\Participant::class)
                                <x-nav.participant-static :active="$active" />
                            @endcan

                            @can('viewAny', \App\Models\Team::class)
                                <x-nav.teams-static :active="$active" />
                            @endcan

                            @can('viewAny', \App\Models\User::class)
                                <x-nav.user-static :active="$active" />
                            @endcan

                            @can('viewAny', \App\Models\CourseType::class)
                                <x-nav.coursetype-static :active="$active" />
                            @endcan

                            @can('viewAny', \App\Models\Role::class)
                                <x-nav.role-static :active="$active" />
                            @endcan

                            @can('viewAny', \App\Models\Price::class)
                                <x-nav.price-static :active="$active" />
                            @endcan
                        </div>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <button type="button" class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" x-description="Heroicon name: outline/bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <div x-data="{ profileDropdown: false }" @click.outside="profileDropdown = false" class="relative ml-3">
                            <div>
                                <button type="button" @click="profileDropdown = !profileDropdown" class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                </button>
                            </div>

                            <div x-show="profileDropdown"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 style="display: none;"
                                 class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->
                                <x-dropdown-links tabindex="-1" id="user-menu-item-0">
                                    Your Profile
                                </x-dropdown-links>

                                <x-dropdown-links tabindex="-1" id="user-menu-item-1">
                                    Settings
                                </x-dropdown-links>

                                <x-dropdown-links @click.prevent="window.livewire.emit('logout')" tabindex="-1" id="user-menu-item-2">
                                    {{ _i('Logout') }}
                                </x-dropdown-links>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div x-description="Mobile menu, show/hide based on menu state." class="sm:hidden" id="mobile-menu" x-show="open" style="display: none;">
                <div class="space-y-1 pt-2 pb-4">
                    <!-- Current: "bg-indigo-50 border-indigo-500 text-indigo-700", Default: "border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700" -->
                    <x-nav.home :active="$active" />

                    @can('viewAny', \App\Models\Course::class)
                        <x-nav.course :active="$active" />
                    @endcan

                    @can('viewAny', \App\Models\Participant::class)
                        <x-nav.participant :active="$active" />
                    @endcan

                    @can('viewAny', \App\Models\Team::class)
                        <x-nav.teams :active="$active" />
                    @endcan

                    @can('viewAny', \App\Models\User::class)
                        <x-nav.user :active="$active" />
                    @endcan

                    @can('viewAny', \App\Models\CourseType::class)
                        <x-nav.coursetype :active="$active" />
                    @endcan

                    @can('viewAny', \App\Models\Role::class)
                        <x-nav.role :active="$active" />
                    @endcan

                    @can('viewAny', \App\Models\Price::class)
                        <x-nav.price :active="$active" />
                    @endcan
                </div>
            </div>
        </nav>

        <div class="py-10">
            <header>
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900">
                        {{ $siteTitle ?? $metaTitle }}
                    </h1>
                </div>
            </header>
            <main>
                <div class="mx-auto sm:px-6 lg:px-8">
                    <!-- Replace with your content -->
                    <div class="px-4 py-8 sm:px-0">
                        {{ $slot }}
                    </div>
                    <!-- /End replace -->
                </div>
            </main>
        </div>
    </div>

</x-layouts.base>
