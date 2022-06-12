<div>
    <form wire:submit.prevent="save({{ $course_data->id }}, {{ $course_data->prices[0]->id }})" class="space-y-8 divide-y divide-gray-200">
        <div class="space-y-8 divide-y divide-gray-200">
            <div>
                <div>
                    <h1 class="text-center text-2xl leading-6 font-medium text-gray-900">{{ $course_data->type->name }}</h1>
                    <p class="text-center mt-1 text-gray-500">{{ $course_data->street }}, {{ $course_data->zipcode }} {{ $course_data->location }} - {{ \Carbon\Carbon::parse($course_data->start)->isoFormat('DD.MM.YYYY, HH:mm') }} Uhr</p>
                </div>
            </div>

            <div class="pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ _i('contact person') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ _i('Information about you as the contact person for this booking.') }}</p>
                </div>

                <div class="sm:col-span-6">
                    <fieldset class="mt-4">
{{--                        <legend class="sr-only">Notification method</legend>--}}
                        <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                            <div class="flex items-center">
                                <input wire:model="samePerson" id="samePerson" type="radio" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="samePerson" class="ml-3 block text-sm font-medium text-gray-700">{{ _i('The contact person is also the participant') }} </label>
                            </div>

                            <div class="flex items-center">
                                <input wire:model="samePerson" id="sms" type="radio" value="0" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="sms" class="ml-3 block text-sm font-medium text-gray-700"> {{ _i('Participant is another person') }} </label>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="company" class="block text-sm font-medium text-gray-700"> {{ _i('company') }} <span class="text-xs text-gray-500">({{ _i('optional') }})</span></label>
                        <div class="mt-1">
                            <input wire:model.lazy="contactPerson.company" id="company" name="company" type="text" autocomplete="company" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="first-name" class="block text-sm font-medium text-gray-700">{{ _i('First name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.firstname" type="text" name="first-name" id="first-name" required autocomplete="first-name" class="@error('contactPerson.firstname') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.firstname')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('contactPerson.firstname')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('A First name is required.') }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last-name" class="block text-sm font-medium text-gray-700">{{ _i('Last name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.lastname" type="text" name="last-name" id="last-name" required autocomplete="last-name" class="@error('contactPerson.lastname') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.lastname')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('contactPerson.lastname')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('A Last name is required.') }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="street-address" class="block text-sm font-medium text-gray-700">{{ _i('Street address') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.street" type="text" name="street-address" id="street-address" required autocomplete="street-address" class="@error('contactPerson.street') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.street')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('contactPerson.street')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('Street address is required.') }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="postal-code" class="block text-sm font-medium text-gray-700"> {{ _i('zipcode') }} </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.zipcode" type="text" name="postal-code" id="postal-code" required autocomplete="postal-code" class="@error('contactPerson.zipcode') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.zipcode')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <!-- Heroicon name: solid/exclamation-circle -->
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('contactPerson.zipcode')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('Zipcode is required.') }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="city" class="block text-sm font-medium text-gray-700">{{ _i('City') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.location" type="text" name="city" id="city" required autocomplete="city" class="@error('contactPerson.location') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.location')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('contactPerson.location')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('City is required.') }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ _i('Email address') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.email" id="email" name="email" type="email" required autocomplete="email" class="@error('contactPerson.email') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.email')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('contactPerson.email')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('Email address is required.') }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">{{ _i('phone') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input wire:model.lazy="contactPerson.phone" id="phone" name="phone" type="text" required autocomplete="phone" class="@error('contactPerson.phone') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('contactPerson.phone')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('contactPerson.phone')
                            <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('Phone number is required.') }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <div class="pt-4">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ _i('participants') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ _i('Information about the participants') }}</p>
                    </div>

                    <div class="space-y-8 divide-y divide-gray-200">
                        <div>
                            @foreach($participants as $participant)
                                <div>
                                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label for="first-name" class="block text-sm font-medium text-gray-700">{{ _i('First name') }}</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input wire:model.lazy="participants.{{ $loop->index }}.firstname" type="text" name="first-name" id="first-name" required autocomplete="given-name" class="@error('participants.' . $loop->index . '.firstname') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('participants.' . $loop->index . '.firstname')
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <!-- Heroicon name: solid/exclamation-circle -->
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                @enderror
                                            </div>
                                            @error('participants.' . $loop->index . '.firstname')
                                                <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('First name is required.') }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="last-name" class="block text-sm font-medium text-gray-700">{{ _i('Last name') }}</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input wire:model.lazy="participants.{{ $loop->index }}.lastname" type="text" name="last-name" id="last-name" required autocomplete="family-name" class="@error('participants.' . $loop->index . '.lastname') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('participants.' . $loop->index . '.lastname')
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <!-- Heroicon name: solid/exclamation-circle -->
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                    </div>
                                                @enderror
                                            </div>
                                            @error('participants.' . $loop->index . '.lastname')
                                                <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('Last name is required.') }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-1">
                                            <label for="date" class="block text-sm font-medium text-gray-700">{{ _i('date of birth') }}</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input wire:model.lazy="participants.{{ $loop->index }}.date_of_birth" id="date" name="date" type="text" required autocomplete="date" placeholder="01.12.1992" class="@error('participants.' . $loop->index . '.date_of_birth') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror placeholder-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @error('participants.' . $loop->index . '.date_of_birth')
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <!-- Heroicon name: solid/exclamation-circle -->
                                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                @enderror
                                            </div>
                                            @error('participants.' . $loop->index . '.date_of_birth')
                                                <p class="mt-2 text-sm text-red-600" id="first-name-error">{{ _i('The date of birth is required.') }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-2">
                                            <div class="flex justify-between">
                                                <label for="email" class="block text-sm font-medium text-gray-700">{{ _i('Email address') }}</label>
                                                <span class="text-xs text-gray-500" id="email-optional">{{ _i('optional') }}</span>
                                            </div>
                                            <div class="mt-1">
                                                <input wire:model.lazy="participants.{{ $loop->index }}.email" id="email" name="email" type="email" autocomplete="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <div class="flex justify-between">
                                                <label for="phone" class="block text-sm font-medium text-gray-700"> {{ _i('phone') }} </label>
                                                <span class="text-xs text-gray-500" id="phone-optional">{{ _i('optional') }}</span>
                                            </div>
                                            <div class="mt-1">
                                                <input wire:model.lazy="participants.{{ $loop->index }}.phone" id="phone" name="phone" type="text" autocomplete="phone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-1">
                                            @if(count($participants) > 1)
                                                <label for="date" class="block text-sm font-medium text-gray-700"> {{ _i('remove participant') }}? </label>
                                                <div class="mt-1 text-center">
                                                    <x-button.link wire:click="removeParticipant({{ $loop->index }})"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" /></svg></x-button.link>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            @if(($course_data->seats - $course_data->participants_count) - count($participants) > 0)
                                <x-button.link wire:click="addParticipant()" class="mt-2 text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg> {{ _i('add another participant') }}</x-button.link>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ _i('Booking overview') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ _i('Summary of the data about your booking') }}</p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ _i('Course details') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course_data->type->name }} - {{ $course_data->street }}, {{ $course_data->zipcode }} {{ $course_data->location }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ _i('date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($course_data->start)->isoFormat('DD.MM.YYYY, dd.') }} {{ \Carbon\Carbon::parse($course_data->start)->isoFormat('HH:mm') }} - {{ \Carbon\Carbon::parse($course_data->end)->isoFormat('HH:mm') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ _i('Price / person') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Price::SIGN[$course_data->prices[0]->currency] }} {{ $course_data->prices[0]->amount_gross }} <span class="text-xs">({!! $course_data->prices[0]->tax_rate ? _i('incl. %s&#37; VAT', $course_data->prices[0]->tax_rate) : _i('VAT free') !!})</span></dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ _i('Total amount') }}</dt>
                            <dd class="mt-1 text-sm font-extrabold text-gray-900">{{ \App\Models\Price::SIGN[$course_data->prices[0]->currency] }} {{ number_format($course_data->prices[0]->amount_gross * count($participants), 2, '.', ',')}} <span class="text-xs font-normal">({!! $course_data->prices[0]->tax_rate ? _i('incl. %s&#37; VAT', $course_data->prices[0]->tax_rate) : _i('VAT free') !!})</span></dd>
                        </div>
                        @if($course_data->prices[0]->description)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ _i('selected price option') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $course_data->prices[0]->description }}</dd>
                            </div>
                        @endif
                        <div class="sm:col-span-2">
                            <fieldset>
                                <legend class="text-sm font-medium text-gray-500">{{ _i('select payment method') }}</legend>

                                <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">
                                    @foreach(unserialize($course_data->prices[0]->payment) as $option => $foo)
                                        <label class="relative bg-white border rounded-lg shadow-sm p-4 flex cursor-pointer focus:outline-none {{ $payment === $option ? 'border-transparent border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-300' }}">
                                            <input wire:model="payment" type="radio" name="payment" required value="{{ $option }}" class="sr-only" aria-labelledby="project-type-0-label" aria-describedby="project-type-0-description-0 project-type-0-description-1">
                                            <div class="flex-1 flex">
                                                <div class="flex flex-col">
                                                    <span id="project-type-0-label" class="block text-sm font-bold text-gray-900"> {{ __('payments.' . $option . '.title')  }} </span>
                                                    <span id="project-type-0-description-0" class="mt-1 flex items-center text-sm text-gray-500"> {{ __('payments.' . $option . '.description') }}</span>
                                                </div>
                                            </div>

                                            @if($payment === $option)
                                                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif

                                            <div class="absolute -inset-px rounded-lg pointer-events-none {{ $payment === $option ?  'border border-indigo-500' : 'border-2 border-transparent' }}" aria-hidden="true"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>

                        <div class="sm:col-span-2">
                            <fieldset class="space-y-5">
                                <legend class="text-sm font-medium text-gray-500">{{ _i('Consents') }}</legend>
                                @if(config('booking.gtc'))
                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="gtc" name="gtc" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="gtc" class="font-medium text-gray-700">{!! _i('I have read and accepted the <a href="%s" target="_blank">terms and conditions</a>.', config('booking.gtc')) !!}</label>
                                        </div>
                                    </div>
                                @endif
                                @if(config('booking.privacy'))
                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="privacy" name="privacy" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="privacy" class="font-medium text-gray-700">{!! _i('I agree that the details from the booking form are collected and processed for the purpose of handling the booking. Detailed information on the handling of user data can be found in the <a href="%s" target="_blank">privacy policy</a>.', config('booking.gtc')) !!}</label>
                                        </div>
                                    </div>
                                @endif
                                @if(config('booking.revocation') && !isset($contactPerson['company']))
                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="revocation" name="revocation" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        @if($course_data->start < \Carbon\Carbon::now()->addDays(14))
                                            <div class="ml-3 text-sm">
                                                <label for="revocation" class="font-medium text-gray-700">{!! _i('I agree that you start the execution of the contract before the expiry of the revocation period of 14 days and in this respect I waive the <a href="%s" target="_blank">right of revocation</a> to which I am entitled.', config('booking.revocation')) !!}</label>
                                            </div>
                                        @else
                                            <div class="ml-3 text-sm">
                                                <label for="revocation" class="font-medium text-gray-700">{!! _i('I have taken note of the <a href="%s" target="_blank">revocation policy</a>.', config('booking.revocation')) !!}</label>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </fieldset>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <x-button.primary class="uppercase px-6 py-3 umami--click--booking-button" type="submit">{{ _i('book with obligation to pay') }}</x-button.primary>
                </div>
            </div>
        </div>
    </form>

    <x-booking.footer />

    <div x-data="{ success: @entangle('bookingSuccessful') }" x-show="success" class="relative z-10" aria-labelledby="modal-title" x-ref="dialog" aria-modal="true" style="display: none;">

        <div x-show="success" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Background backdrop, show/hide based on modal state." class="fixed inset-0 bg-gray-500 transition-opacity"></div>


        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center sm:min-h-screen pt-60 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                <div x-show="success" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-description="Modal panel, show/hide based on modal state." class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" x-description="Heroicon name: outline/check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ _i('booking successful') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {!! _i('Your booking was successful. We have <strong>additionally</strong> sent you an email with further information.') !!}
                                </p>
                            </div>
                        </div>
                    </div>
{{--                    <div class="mt-5 sm:mt-6">--}}
{{--                        <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">--}}
{{--                            Go back to dashboard--}}
{{--                        </button>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

    <div>
        @if(($course_data->seats - $course_data->participants_count) < 1 && !$bookingSuccessful || $course_data->cancelled || $course_data->start < Carbon\Carbon::now()) {{-- the booking isn't possible anymore --}}
            <div class="relative z-10" aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">
                <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Background backdrop, show/hide based on modal state." class="fixed inset-0 bg-gray-500 transition-opacity"></div>

                <div class="fixed z-10 inset-0 overflow-y-auto">
                    <div class="flex items-end justify-center sm:min-h-screen pt-60 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

                        <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-description="Modal panel, show/hide based on modal state." class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                            <div>
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        {{ _i('Booking not possible') }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            {{ _i('Unfortunately, booking this course is no longer possible.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                                <div class="mt-5 sm:mt-6">
                                    <a type="button" href="javascript:history.back()" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                        {{ _i('back') }}
                                    </a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div>
        @if(($course_data->seats - $course_data->participants_count) - count($participants) < 0)
            <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
                <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                    <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <!-- Heroicon name: outline/exclamation -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-gray-900">{{ _i('Not enough free places!') }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ _i('The free places are no longer sufficient for the desired number of participants! Please remove participants.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "{{ $course_data->type->name }} - {{ $course_data->prices[0]->title }}",
      "description": "{{ $course_data->prices[0]->description }}",
      "startDate": "{{ \Carbon\Carbon::parse($course_data->start)->isoFormat('YYYY-MM-DDTHH:mm') }}",
      "endDate": "{{ \Carbon\Carbon::parse($course_data->end)->isoFormat('YYYY-MM-DDTHH:mm') }}",
      "eventStatus": "{{ $course_data->cancelled ? 'https://schema.org/EventCancelled' : 'https://schema.org/EventScheduled' }}",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "location": {
        "@type": "Place",
        "name": "{{ $course_data->seminar_location }}",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "{{ $course_data->street }}",
          "addressLocality": "{{ $course_data->location }}",
          "postalCode": "{{ $course_data->zipcode }}",
          "addressCountry": "DE"
        }
      },
{{--      "organizer": {--}}
{{--		"@type": "Organization",--}}
{{--		"name": "courserv.io",--}}
{{--		"url": "https://courserv.io"--}}
{{--	},--}}
      "performer": {
        "@type": "Person",
        "name": "TBA"
      },
      "offers": [
            {
                "@type": "Offer",
                "name": "{{ $course_data->type->name }} - {{ $course_data->prices[0]->title }}",
                "price": "{{ $course_data->prices[0]->amount_gross }}",
                "priceCurrency": "{{ $course_data->prices[0]->currency }}",
                "validFrom": "{{ \Carbon\Carbon::parse($course_data->created_at)->isoFormat('YYYY-MM-DD') }}",
                "url": "{{ route('booking', ['course' => Hashids::encode($course_data->id), 'price' => Hashids::encode($course_data->prices[0]->id)]) }}",
                "availability": "{{ ($course_data->seats - $course_data->participants_count) - count($participants) <= 0 || $course_data->cancelled ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock' }}"
            }
        ]
    }
    </script>

</div>
