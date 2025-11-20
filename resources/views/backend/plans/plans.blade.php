<div>
    @if($plan)
    <x-jet-dialog-modal wire:model="plan">
        <x-slot name="title">
            @if($plan['id'] != null)
            {{ __('Update Plan') }}
            @else
            {{ __('New Plan') }}
            @endif
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="plan.name" placeholder="Enter the Plan Name" />
                    <x-jet-input-error for="plan.name" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-jet-label for="price" value="{{ __('Price') }}" />
                    <div class="flex mt-1">
                        <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            {{ config('app.settings.currency.symbol') }}
                        </span>
                        <x-jet-input id="price" type="number" max="99999999" min="1" class="block w-full rounded-l-none" wire:model.defer="plan.price" placeholder="Enter the Plan Price" />
                    </div>
                    <x-jet-input-error for="plan.price" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-jet-label for="validity" value="{{ __('Validity') }}" />
                    <div class="flex mt-1">
                        <x-jet-input id="validity" type="number" class="block w-full rounded-r-none" wire:model.defer="plan.validity" placeholder="Enter the Plan Validity" />
                        <select class="border-gray-300 border-l-0 rounded-r shadow-sm block cursor-pointer" wire:model.defer="plan.validity_type">
                            <option value="minute">{{ _('Minutes') }}</option>
                            <option value="hour">{{ _('Hours') }}</option>
                            <option value="day">{{ _('Days') }}</option>
                            <option value="week">{{ _('Weeks') }}</option>
                            <option value="month">{{ _('Months') }}</option>
                            <option value="year">{{ _('Years') }}</option>
                        </select>
                    </div>
                    <x-jet-input-error for="plan.validity" class="mt-2" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-button wire:click="handle">
                @if($plan['id'])
                {{ __('Update') }}
                @else
                {{ __('Add') }}
                @endif
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
    @endif
    <section class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($plans as $plan)
        <div class="bg-white rounded-lg shadow">
            <div class="flex justify-between items-center p-5">
                <div class="space-y-3">
                    <h4 class="cursor-pointer" wire:click="update({{ $plan->id }})">{{ $plan->name }}</h4>
                    <div class="flex items-center mt-3 space-x-2 md:space-x-5 text-xs md:text-sm text-gray-400 font-semibold">
                        @if($plan->is_active)
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
                        <div class="flex gap-1 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $plan->validity }} {{ __(ucfirst($plan->validity_type) . ( $plan->validity > 1 ? 's' : '')) }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="font-bold text-4xl">{{ config('app.settings.currency.symbol') }}{{ $plan->price }}</div>
                </div>
                <div class="flex">
                    <button wire:click="update({{ $plan->id }})" class="has-tooltip flex justify-center rounded-full text-gray-600 hover:bg-gray-100 p-2 md:p-3 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-6 w-4 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        <span class="tooltip">{{ __('Edit') }}</span>
                    </button>
                    <a href="{{ route('plans.plan.map', $plan->id) }}" class="has-tooltip flex justify-center rounded-full text-indigo-600 hover:bg-indigo-100 p-2 md:p-3 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-6 w-4 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                        </svg>
                        <span class="tooltip">{{ __('Map Numbers') }}</span>
                    </a>
                    @if($plan->is_active)
                    <div class="has-tooltip flex justify-center rounded-full text-orange-600 hover:bg-orange-100 p-2 md:p-3 cursor-pointer" wire:click="enableDisable({{ $plan->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-6 w-4 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                        </svg>
                        <span class="tooltip">{{ __('Disable') }}</span>
                    </div>
                    @else
                    <div class="has-tooltip flex justify-center rounded-full text-green-600 hover:bg-green-100 p-2 md:p-3 cursor-pointer" wire:click="enableDisable({{ $plan->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-6 w-4 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        <span class="tooltip">{{ __('Enable') }}</span>
                    </div>
                    @endif
                    <div class="has-tooltip flex justify-center rounded-full text-red-600 hover:bg-red-100 p-2 md:p-3 cursor-pointer" onclick="confirm('Are you sure to delete this plan?')  || event.stopImmediatePropagation()" wire:click="delete({{ $plan->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-6 w-4 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="tooltip">{{ __('Delete') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    <div class="w-full flex justify-end mt-5">
        <button class="bg-primary hover:bg-gray-900 text-white px-10 py-2 sm:rounded-lg flex items-center space-x-2" wire:click="add()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
            </svg>
            <span>{{ __('Add Plan') }}</span>
        </button>
    </div>
</div>