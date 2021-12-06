<div>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ _i('team name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ _i('Display Name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ _i('Description') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 tracking-wider">
                                @can('create', \App\Models\Team::class)<x-button.link wire:click="create">{{ _i('Add Team') }}</x-button.link>@endcan
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($teams as $team)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $team->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $team->display_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $team->description ?? 'none' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @can('update', $team)
                                        <x-button.link wire:click="edit({{ $team->id }})">Edit</x-button.link>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        <!-- More people... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">{{ _i('edit team') }}</x-slot>
            <x-slot name="content">
                <div class="space-y-3">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ _i('team name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" wire:model.lazy="editing.name" name="{{ _i('team name') }}" id="name" class="lowercase block w-full pr-10 sm:text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block border-gray-300 @error('editing.name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror " placeholder="{{ _i('team name') }}" @error('editing.name') aria-invalid="true" aria-describedby="name-error" @enderror>
                            @error('editing.name')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('editing.name')
                            <p class="mt-2 text-sm text-red-600" id="name-error">{{ $errors->first('editing.name') }}</p>
                        @else
                            <p class="mt-2 text-xs text-gray-500" id="email-description">{{ _i('The internal used team name.') }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700">{{ _i('Display Team Name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" wire:model.lazy="editing.display_name" name="display_name" id="display_name" class="block w-full pr-10 sm:text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block border-gray-300 @error('editing.display_name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror " placeholder="{{ _i('Display Team Name') }}" @error('editing.display_name') aria-invalid="true" aria-describedby="display_name-error" @enderror>
                            @error('editing.display_name')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('editing.display_name')
                            <p class="mt-2 text-sm text-red-600" id="name-error">{{ $errors->first('editing.display_name') }}</p>
                        @else
                            <p class="mt-2 text-xs text-gray-500" id="name-description">{{ _i('The displayed team name.') }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ _i('Description') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea wire:model.lazy="editing.description" name="description" id="description" class="block w-full pr-10 sm:text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block border-gray-300 @error('editing.description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror " placeholder="{{ _i('Description') }}" @error('editing.description') aria-invalid="true" aria-describedby="description-error" @enderror></textarea>
                            @error('editing.description')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <!-- Heroicon name: solid/exclamation-circle -->
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </div>
                        @error('editing.description')
                            <p class="mt-2 text-sm text-red-600" id="name-error">{{ $errors->first('editing.description') }}</p>
                        @else
                            <p class="mt-2 text-xs text-gray-500" id="description-description">{{ _i('A description for this team. (optional)') }}</p>
                        @enderror
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>
                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
