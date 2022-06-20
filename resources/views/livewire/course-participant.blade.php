<div>
    <div class="space-y-4 py-4">
{{--        <div class="flex justify-between">--}}
{{--            <div class="flex w-2/4 space-x-4">--}}
{{--                <x-input.text wire:model="filters.search" placeholder="{{ _i('Search...') }}" />--}}

{{--                <x-button.link wire:click="toggleShowFilters">@if ($showFilters) Hide @endif Advanced Search...</x-button.link>--}}
{{--            </div>--}}

{{--            <div class="flex items-center space-x-2">--}}
{{--                <x-input.group borderless paddingless for="perPage" label="Per Page">--}}
{{--                    <x-input.select wire:model="perPage" id="perPage">--}}
{{--                        <option value="10">10</option>--}}
{{--                        <option value="25">25</option>--}}
{{--                        <option value="50">50</option>--}}
{{--                    </x-input.select>--}}
{{--                </x-input.group>--}}

{{--                @can('create', \App\Models\Course::class)--}}
{{--                    <x-button.primary wire:click="create"><x-icon.plus /> {{ _i('Add Course') }}</x-button.primary>--}}
{{--                @endcan--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Advanced Search -->
{{--        <div>--}}
{{--            @if ($showFilters)--}}
{{--                <div class="bg-cool-gray-200 relative flex rounded p-4 shadow-inner">--}}
{{--                    <div class="w-1/2 space-y-4 pr-2">--}}
{{--                        <x-input.group inline for="filter-courseType" label="{{ _i('course type') }}">--}}
{{--                            <x-input.select wire:model="filters.courseType" id="filter-courseType">--}}
{{--                                <option value="" disabled>{{ _i('select course type...') }}</option>--}}
{{--                                @foreach($courseTypes as $category => $courseType)--}}
{{--                                    <optgroup label="{{ $category }}">--}}
{{--                                        @foreach($courseType as $ct)--}}
{{--                                            <option value="{{ $ct['id'] }}">{{ $ct['name'] }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </optgroup>--}}
{{--                                @endforeach--}}
{{--                            </x-input.select>--}}
{{--                        </x-input.group>--}}

{{--                        <x-input.group inline for="filter-team" label="{{ _i('team') }}">--}}
{{--                            <x-input.select wire:model="filters.team" id="filter-team">--}}
{{--                                <option value="" disabled>{{ _i('select team...') }}</option>--}}
{{--                                @foreach($teams as $team)--}}
{{--                                    <option value="{{ $team['id'] }}">{{ $team['display_name'] }}</option>--}}
{{--                                @endforeach--}}
{{--                            </x-input.select>--}}
{{--                        </x-input.group>--}}

{{--                        <x-input.group inline for="filter-showCancelled" label="{{ _i('cancelled courses') }}">--}}
{{--                            <x-input.select wire:model="filters.showCancelled" id="filter-showCancelled">--}}
{{--                                <option value="">{{ _i('don\'t show cancelled courses') }}</option>--}}
{{--                                <option value="true">{{ _i('show cancelled courses') }}</option>--}}
{{--                            </x-input.select>--}}
{{--                        </x-input.group>--}}
{{--                    </div>--}}

{{--                    <div class="w-1/2 space-y-4 pl-2">--}}
{{--                        <x-input.group inline for="filter-date-min" label="{{ _i('minimum date') }}">--}}
{{--                            <x-input.date wire:model="filters.date-min" id="filter-date-min" :options="$search_options"/>--}}
{{--                        </x-input.group>--}}

{{--                        <x-input.group inline for="filter-date-max" label="{{ _i('maximum date') }}">--}}
{{--                            <x-input.date wire:model="filters.date-max" id="filter-date-max" :options="$search_options"/>--}}
{{--                        </x-input.group>--}}

{{--                        <x-button.link wire:click="resetFilters" class="absolute right-0 bottom-0 p-4">{{ _i('reset filters') }}</x-button.link>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}

        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.heading></x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('lastname') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('firstname') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('date of birth') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('street') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('location') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('phone') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('email') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('booked on') }}</x-table.heading>
                    <x-table.heading sortable multi-column>{{ _i('price') }}</x-table.heading>
                    <x-table.heading></x-table.heading>
                    <x-table.heading></x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @foreach($participants as $participant)
                        <x-table.row class="{{ $participant->cancelled ? 'line-through' : '' }}">
                            <x-table.cell><x-input.checkbox wire:model="select" value="{{ $participant->id }}" /></x-table.cell>
                            <x-table.cell>{{ $participant->lastname }}</x-table.cell>
                            <x-table.cell>{{ $participant->firstname }}</x-table.cell>
                            <x-table.cell>
                                @can('viewAny', $participant)
                                    {{ \Carbon\Carbon::parse($participant->date_of_birth)->isoFormat('DD.MM.YYYY') }}
                                @else
                                    <span class="blur-sm">XX.XX.XXXX</span>
                                @endcan
                            </x-table.cell>
                            <x-table.cell>
                                @can('viewAny', $participant)
                                    {{ $participant->street }}
                                @else
                                    <span class="blur">{{ substr(sha1(time()), 0, strlen($participant->street)) }}</span>
                                @endcan
                            </x-table.cell>
                            <x-table.cell>
                                @can('viewAny', $participant)
                                    {{ $participant->zipcode }} {{ $participant->location }}
                                @else
                                    <span class="blur">12345 {{ substr(sha1(time()), 0, strlen($participant->location)) }}</span>
                                @endcan
                            </x-table.cell>
                            <x-table.cell>
                                @can('viewAny', $participant)
                                    {{ $participant->phone }}
                                @else
                                    <span class="blur">{{ substr(sha1(time()), 0, strlen($participant->phone)) }}</span>
                                @endcan
                            </x-table.cell>
                            <x-table.cell>
                                @can('viewAny', $participant)
                                    {{ $participant->email }}
                                @else
                                    <span class="blur">{{ substr(sha1(time()), 0, strlen($participant->email)) }}</span>
                                @endcan
                            </x-table.cell>
                            <x-table.cell>{{ \Carbon\Carbon::parse($participant->created_at)->isoFormat('DD.MM.YYYY') }}</x-table.cell>
                            <x-table.cell>{{ $participant->currency }} {{ $participant->price_gross }}</x-table.cell>
                            <x-table.cell>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->participated ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($can_update)
                                        <x-button.badge wire:click="participate({{ $participant->id }})">{{ $participant->participated ? _i('participated') : _i('not participated') }}</x-button.badge>
                                    @else
                                        <x-button.badge wire:click="participate({{ $participant->id }})" disabled>{{ $participant->participated ? _i('participated') : _i('not participated') }}</x-button.badge>
                                    @endif
                                </span>
                            </x-table.cell>
                            <x-table.cell>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->payed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($can_update)
                                        <x-button.badge wire:click="pay({{ $participant->id }})">{{ __('payments.' . $participant->payment . '.title') }}</x-button.badge>
                                    @else
                                        <x-button.badge wire:click="pay({{ $participant->id }})" disabled>{{ __('payments.' . $participant->payment . '.title') }}</x-button.badge>
                                    @endif
                                </span>
                            </x-table.cell>
{{--                            <x-table.cell>--}}
{{--                                @can('update', $course)--}}
{{--                                    <x-button.link wire:click="participant({{ $course->id }})">{{ _i('participants') }}</x-button.link>--}}
{{--                                @endcan--}}
{{--                            </x-table.cell>--}}
{{--                            <x-table.cell>--}}
{{--                                @can('update', $course)--}}
{{--                                    <x-button.link wire:click="edit({{ $course->id }})">{{ _i('edit') }}</x-button.link>--}}
{{--                                @endcan--}}
{{--                            </x-table.cell>--}}
                        </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>
{{--            <div>--}}
{{--                {{ $courses->links() }}--}}
{{--            </div>--}}
        </div>
    </div>
</div>
