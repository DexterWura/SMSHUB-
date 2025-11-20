<div class="space-y-16">
    @if(count($maps) > 0)
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($maps as $map)
        <div class="bg-white rounded-lg shadow">
            <div class="flex justify-between items-center p-5">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-primary font-semibold">{{ $map->plan->name }}</h3>
                    </div>
                    <div class="flex items-center mt-3 space-x-2 md:space-x-5 text-xs md:text-sm text-gray-400 font-semibold">
                        @if($map->plan->is_active)
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
                        <div class="flex gap-1 text-pink-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ config('app.settings.currency.symbol') }}{{ $map->plan->price }}</span>
                        </div>
                        <div class="flex gap-1 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $map->plan->validity }} {{ __(ucfirst($map->plan->validity_type) . ( $map->plan->validity > 1 ? 's' : '')) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <div class="has-tooltip flex justify-center rounded-full text-red-600 hover:bg-red-100 p-2 md:p-3 cursor-pointer" wire:click="remove({{ $map->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-6 w-5 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="tooltip">{{ __('Remove Plan') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    @endif
    <x-jet-form-section submit="add">
        <x-slot name="title">
            {{ __('Add Plans') }}
        </x-slot>

        <x-slot name="description">
            {{ __('You can add Plans using this form.') }}
        </x-slot>
        
        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="plan" value="{{ __('Choose Plan to Add') }}" />
                <div class="relative">
                    <select id="plan" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model="plan" required>
                        <option selected disabled value="null">{{ __('Select Plan') }}</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} - {{ config('app.settings.currency.symbol') }}{{ $plan->price }}</option>
                        @endforeach
                    </select>
                </div>
                <x-jet-input-error for="plan" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="added">
                {{ __('Added.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __('Add') }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>
</div>