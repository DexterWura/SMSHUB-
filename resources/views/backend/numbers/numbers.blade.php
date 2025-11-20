<div>
    @if($number)
    <x-jet-dialog-modal wire:model="number">
        <x-slot name="title">
            @if($number['id'] != null)
            {{ __('Update Number') }}
            @else
            {{ __('New Number') }}
            @endif
        </x-slot>
        <x-slot name="content">
            @if($error)
            <div class="bg-red-50 rounded-lg p-3 mb-5">
                <div class="flex justify-start items-start space-x-5">
                    <div class="text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="space-y-3">
                        <p class="text-sm text-red-800 font-medium">{{ __('Error!') }} {{ __($error) }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <x-jet-label for="number" value="{{ __('Number') }}" />
                    <x-jet-input id="number" type="number" class="mt-1 block w-full" wire:model.defer="number.number" placeholder="Enter the Number with Country Code" />
                    <x-jet-input-error for="number.number" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-jet-label for="country" value="{{ __('Country') }}" />
                    <select id="country" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model.defer="number.country">
                        <option selected disabled value="null">{{ __('Select a Country') }}</option>
                        @foreach(\App\Services\Util::$country as $code => $country)
                        <option value="{{ $code }}">{{ $country }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="number.country" class="mt-2" />
                </div>
                <div class="{{ $number['id'] ? 'col-span-2' : '' }}">
                    <x-jet-label for="type" value="{{ __('Type') }}" />
                    <select id="type" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model.defer="number.type">
                        <option selected disabled value="null">{{ __('Select a Type') }}</option>
                        @foreach($types as $key => $type)
                        <option value="{{ $key }}">{{ __($type) }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="number.type" class="mt-2" />
                </div>
                @if($number['id'] == null)
                <div>
                    <x-jet-label for="status" value="{{ __('Status') }}" />
                    <select id="status" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model.defer="number.status">
                        <option value="1">{{ __('Enabled') }}</option>
                        <option value="0">{{ __('Disabled') }}</option>
                    </select>
                    <x-jet-input-error for="number.status" class="mt-2" />
                </div>
                @endif
                <div class="col-span-2">
                    <x-jet-label for="meta" value="{{ __('Meta Description (Optional)') }}" />
                    <textarea id="meta" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full resize-y border" placeholder="Enter any Details" wire:model.defer="number.meta"></textarea>
                    <x-jet-input-error for="number.meta" class="mt-2" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-button wire:click="handle">
                @if($number['id'])
                {{ __('Update') }}
                @else
                {{ __('Add') }}
                @endif
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
    @endif
    @if($showManageAssignees)
    <x-jet-dialog-modal maxWidth="4xl" wire:model="showManageAssignees">
        <x-slot name="title">
            {{ __('Manage Assignees') }}
        </x-slot>
        <x-slot name="content">
            @if(count($assignees))
            <p class="text-sm mb-3">{{ __('Below users will have access to') }} {{ $numberToAssign->number }}</p>
            @else
            <div class="my-5 text-center text-gray-300">{{ __('No Assignees') }}</div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($assignees as $assignee)
                <div class="bg-white rounded-lg border">
                    <div class="flex justify-between p-5">
                        <div class="space-y-1">
                            <div class="flex space-x-3">
                                <h4 class="font-semibold">{{ $assignee->user->name }}</h4>
                                @if($assignee->user->role == 7)
                                <div class="bg-red-200 text-red-900 rounded-full px-2 py-1 text-xs">{{ __('Admin') }}</div>
                                @endif
                            </div>
                            <h5 class="text-sm text-gray-400">{{ $assignee->user->email }}</h5>
                        </div>
                        <div>
                            <img class="h-10 w-10 object-cover rounded-full"
                                src="https://s.gravatar.com/avatar/{{ md5($assignee->user->email) }}?s=80"
                                alt="img">
                        </div>
                    </div>
                    <button wire:click="removeAssignee({{ $assignee->id }})" class="w-full bg-red-700 text-white rounded-b-lg py-2 text-xs">
                        {{ __('Remove') }}
                    </button>
                </div>
                @endforeach
            </div>
            <div class="mt-16 w-full space-y-3">
                <div class="text-md">{{ __('Add Assignee') }}</div>
                <select name="user" id="user" wire:model="user" class="w-full py-2 focus:ring-lime-500 focus:border-lime-500 block sm:text-sm border border-gray-200 rounded-lg">
                    <option value="null" disabled selected>{{ __('Select User') }}</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                    @endforeach
                </select>
                <button wire:click="assign" class="bg-gray-800 text-white rounded-lg block w-full py-2 text-xs">{{ __('Assign') }}</button>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('showManageAssignees', false)">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>
    @endif
    <div class="space-y-5">
        <div class="flex space-x-3 items-center">
            <input type="text" wire:model.debounce.500ms="search" name="search" id="search" autocomplete="tsms-search" class="flex-1 py-4 focus:ring-lime-500 focus:border-lime-500 block w-full shadow sm:text-sm border-0 rounded-lg" placeholder="Search by Number">
            <select name="status" id="status" wire:model="filters.type" class="py-4 focus:ring-lime-500 focus:border-lime-500 block shadow sm:text-sm border-0 rounded-lg">
                <option value="null">{{ __('Status Filter') }}</option>
                @foreach($types as $key => $type)
                <option value="{{ $key }}">{{ __($type) }}</option>
                @endforeach
            </select>
            <select name="sort_by" id="sort_by" wire:model="orderby" class="py-4 focus:ring-lime-500 focus:border-lime-500 block shadow sm:text-sm border-0 rounded-lg">
                <option value="id">{{ __('Sort By') }}</option>
                <option value="number">{{ __('Number') }}</option>
                <option value="country">{{ __('Country') }}</option>
                <option value="status">{{ __('Status') }}</option>
                <option value="updated_at">{{ __('Updated Date') }}</option>
            </select>
            <div x-data="{ order: 'asc' }">
                <span class="text-xs">{{ __('ORDER') }}</span>
                <div class="flex justify-center cursor-pointer" wire:click="changeOrder">
                    <svg x-show="order === 'asc'" x-on:click="order = 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                    </svg>
                    <svg x-show="order === 'desc'" x-on:click="order = 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            @foreach($numbers as $number)
            <div class="border-b py-4 px-5">
                <div class="flex justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-primary font-semibold">+{{ $number->number }}</h3>
                            <span class="{{ $bg[$number->type] }} {{ $text[$number->type] }} px-2 py-1 text-xs font-bold rounded-full">{{ __($types[$number->type]) }}</span>
                        </div>
                        <div class="flex items-center mt-3 space-x-2 md:space-x-5 text-xs md:text-sm text-gray-400 font-semibold">
                            @if($number->status)
                            <div class="flex gap-1 text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ __('Active') }}</span>
                            </div>
                            @else
                            <div class="flex gap-1 text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ __('Inactive') }}</span>
                            </div>
                            @endif
                            <div class="flex gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $this->getCountryName($number->country) }}</span>
                            </div>
                            @if($number->meta)
                            <div class="flex gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                </svg>
                                <span>{{ $number->meta }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1 md:gap-2">
                        @if($number->type == 2)
                        <div class="has-tooltip flex justify-center rounded-full text-emerald-600 hover:bg-emerald-100 p-2 md:p-3 cursor-pointer" wire:click="manageAssignees({{ $number->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <span class="tooltip">{{ __('Manage Assignees') }}</span>
                        </div>
                        @endif
                        @if($number->type == 3 || $number->type == 4)
                        <a href="{{ route('numbers.number.map', $number->id) }}" class="has-tooltip flex justify-center rounded-full text-purple-600 hover:bg-purple-100 p-2 md:p-3 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
                            </svg>
                            <span class="tooltip">{{ __('Manage Plans') }}</span>
                        </a>
                        @endif
                        <div class="has-tooltip flex justify-center rounded-full hover:bg-slate-100 p-2 md:p-3 cursor-pointer" wire:click="set({{ $number->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <span class="tooltip">{{ __('Edit') }}</span>
                        </div>
                        @if($number->status)
                        <div class="has-tooltip flex justify-center rounded-full text-orange-600 hover:bg-orange-100 p-2 md:p-3 cursor-pointer" wire:click="toggleStatus({{ $number->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                            </svg>
                            <span class="tooltip">{{ __('Disable') }}</span>
                        </div>
                        @else
                        <div class="has-tooltip flex justify-center rounded-full text-green-600 hover:bg-green-100 p-2 md:p-3 cursor-pointer" wire:click="toggleStatus({{ $number->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            <span class="tooltip">{{ __('Enable') }}</span>
                        </div>
                        @endif
                        <div class="has-tooltip flex justify-center rounded-full text-cyan-600 hover:bg-cyan-100 p-2 md:p-3 cursor-pointer" onclick="confirm('This action will delete all the messages arrived at this number. Are you sure?')  || event.stopImmediatePropagation()" wire:click="truncate({{ $number->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.5 2a3.5 3.5 0 101.665 6.58L8.585 10l-1.42 1.42a3.5 3.5 0 101.414 1.414l8.128-8.127a1 1 0 00-1.414-1.414L10 8.586l-1.42-1.42A3.5 3.5 0 005.5 2zM4 5.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm0 9a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" clip-rule="evenodd" />
                                <path d="M12.828 11.414a1 1 0 00-1.414 1.414l3.879 3.88a1 1 0 001.414-1.415l-3.879-3.879z" />
                            </svg>
                            <span class="tooltip">{{ __('Truncate') }}</span>
                        </div>
                        <div class="has-tooltip flex justify-center rounded-full text-red-600 hover:bg-red-100 p-2 md:p-3 cursor-pointer" onclick="confirm('Are you sure to delete this number. This related records as well?')  || event.stopImmediatePropagation()" wire:click="delete({{ $number->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-8 w-5 md:w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="tooltip">{{ __('Delete') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="w-full flex justify-end">
            <button class="bg-primary hover:bg-gray-900 text-white px-10 py-2 sm:rounded-lg flex items-center space-x-2" wire:click="add()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
                </svg>
                <span>{{ __('Add Number') }}</span>
            </button>
        </div>
    </div>
</div>