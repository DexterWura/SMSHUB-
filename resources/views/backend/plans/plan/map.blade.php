<div class="space-y-16">
    @if(count($maps) > 0)
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($maps as $map)
        <div class="bg-white rounded-lg shadow">
            <div class="flex justify-between items-center p-5">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-primary font-semibold">+{{ $map->number->number }}</h3>
                    </div>
                    <div class="flex items-center mt-3 space-x-2 md:space-x-5 text-xs md:text-sm text-gray-400 font-semibold">
                        @if($map->number->status)
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
                        <div class="flex gap-1 {{ $text[$map->number->type] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ __($types[$map->number->type]) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <div class="has-tooltip flex justify-center rounded-full text-red-600 hover:bg-red-100 p-2 md:p-3 cursor-pointer" wire:click="remove({{ $map->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 md:h-6 w-5 md:w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="tooltip">{{ __('Remove from Plan') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    @endif
    <x-jet-form-section submit="add">
        <x-slot name="title">
            {{ __('Add Numbers') }}
        </x-slot>

        <x-slot name="description">
            {{ __('You can add more Shared or Private Buy Type Numbers using this form.') }}
        </x-slot>
        
        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="number" value="{{ __('Choose Number to Add') }}" />
                <div class="relative">
                    <select id="number" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model="number" required>
                        <option selected disabled value="null">{{ __('Select Number') }}</option>
                        @foreach($numbers as $number)
                        <option value="{{ $number->id }}">+{{ $number->number }} - {{ __($types[$number->type]) }}</option>
                        @endforeach
                    </select>
                </div>
                <x-jet-input-error for="number" class="mt-2" />
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