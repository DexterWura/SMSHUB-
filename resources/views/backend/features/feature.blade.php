<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Features') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can manage the Custom Features of your tSMS Homepage.') }}
    </x-slot>
    
    <x-slot name="form">
        @if($addFeature || $updateFeature)
            <div class="col-span-6 flex justify-between">
                <x-jet-secondary-button class="mr-2" wire:click="clearAddUpdate">
                    <i class="fas fa-caret-left"></i> <span class="ml-2">{{ __('Back') }}</span>
                </x-jet-secondary-button>
                @if(isset($feature['lang']))
                <div class="bg-gray-200 rounded-md px-4 py-2 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                    </svg>
                    <span>{{ __('Add translations for') }} {{ $feature['lang_text'] }} ({{ $feature['lang'] }})</span>
                </div>
                @endif
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="title" value="{{ __('Name') }}" />
                <x-jet-input id="title" type="text" class="mt-1 block w-full" placeholder="Feature Name" wire:model.defer="feature.title"/>
                <x-jet-input-error for="feature.title" class="mt-2" />
            </div>
            @if(!isset($feature['lang']))
            <div x-data="{ icon: '{{ $feature['icon'] }}' }" class="col-span-6 sm:col-span-4">
                <x-jet-label for="icon" value="{{ __('Icon') }}" />
                <div class="relative">
                    <x-jet-input x-model="icon" id="icon" type="text" class="mt-1 block w-full" placeholder="Feature Icon" wire:model.defer="feature.icon"/>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3"><i :class="icon"></i></div>
                </div>
                <small>
                    {{ __('You can select icons from Font Awesome by visiting ') }}
                    <a class="font-bold underline" href="https://fontawesome.com/icons?m=free" target="_blank" rel="noopener noreferrer">{{ __('this link.') }}</a>
                    <br>
                    {{ __('Example:') }} <strong>fas fa-dollar-sign</strong>
                </small>
                <x-jet-input-error for="feature.icon" class="mt-2" />
            </div>
            @endif
            <div class="col-span-6">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <textarea id="description" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full resize-y border" wire:model.defer="feature.description"></textarea>
                <x-jet-input-error for="feature.description" class="mt-2" />
            </div>
            @if(isset($feature['id']) && !isset($feature['lang']))
            <div class="col-span-6">
                <x-jet-label for="lang" value="{{ __('Add Translations') }} ({{ __('Optional') }})" />
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 mt-2">
                    @foreach(config('app.locales') as $index => $locale)
                    @if($locale != config('app.settings.language'))
                    <button wire:click="translate('{{ $locale }}')" type="button" class="{{ $this->isTranslated($locale) ? 'bg-green-600' : 'bg-gray-800' }} text-white text-sm px-3 py-2 rounded-md flex items-center space-x-2">
                        @if($this->isTranslated($locale))
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        @endif
                        <span>{{ config('app.locales_text')[$index] }}</span>
                    </button>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="col-span-6 -mt-4">
            @if(count($features))
                @foreach($features as $feature)
                <div class="bg-gray-800 text-white rounded-md px-5 py-4 mt-3 flex justify-between items-center">
                    <div class="flex space-x-3">
                        <div classs="text-xs"><i class="{{ $feature->icon }}"></i></div>
                        <div>
                            {{ $feature->title }}
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="cursor-pointer" wire:click="showUpdate({{ $feature->id }})"><i class="fas fa-edit"></i></div>
                        <div class="cursor-pointer" wire:click="delete({{ $feature->id }})"><i class="fas fa-trash-alt"></i></div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="flex justify-center text-gray-600 text-sm pt-5 pb-3">{{ __('It is a Empty Space!') }}</div>
                <div class="flex justify-center text-5xl">🥺</div>
            @endif
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>
        @if($addFeature || $updateFeature)
            @if($addFeature)
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:click="add">
                    {{ __('Add') }}
                </button>
            @else
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:click="update">
                    {{ __('Update') }}
                </button>
            @endif
        @else
            <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition ease-in-out duration-150" wire:click="$toggle('addFeature')">
                {{ __('Add Feature') }}
            </button>
        @endif
    </x-slot>
</x-jet-form-section>