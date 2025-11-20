<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Advance') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can control here, advance settings like adding Custom CSS or JS, adding HTML code to Header or Footer and configuring API Keys for advance access.') }}
    </x-slot>
    
    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label for="homepage_header" value="{{ __('Homepage Header') }}" />
            <textarea id="homepage_header" class="form-input border-gray-300 rounded-md shadow-sm mt-4 block w-full resize-y border" placeholder="Enter your HTML Code here" wire:model.defer="state.homepage.header"></textarea>
            <x-jet-input-error for="homepage_header" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="homepage_footer" value="{{ __('Homepage Footer') }}" />
            <textarea id="homepage_footer" class="form-input border-gray-300 rounded-md shadow-sm mt-4 block w-full resize-y border" placeholder="Enter your HTML Code here" wire:model.defer="state.homepage.footer"></textarea>
            <x-jet-input-error for="homepage_footer" class="mt-2" />
        </div>
        <div class="col-span-6 border-dashed border"></div>
        <div class="col-span-6">
            <x-jet-label for="global_css" value="{{ __('Global CSS') }}" />
            <textarea id="global_css" class="form-input border-gray-300 rounded-md shadow-sm mt-4 block w-full resize-y border" placeholder="Enter your CSS Code here" wire:model.defer="state.global.css"></textarea>
            <x-jet-input-error for="global_css" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="global_js" value="{{ __('Global JS') }}" />
            <textarea id="global_js" class="form-input border-gray-300 rounded-md shadow-sm mt-4 block w-full resize-y border" placeholder="Enter your JS Code here" wire:model.defer="state.global.js"></textarea>
            <x-jet-input-error for="global_js" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="global_header" value="{{ __('Global Header') }}" />
            <textarea id="global_header" class="form-input border-gray-300 rounded-md shadow-sm mt-4 block w-full resize-y border" placeholder="Enter your HTML Code here" wire:model.defer="state.global.header"></textarea>
            <x-jet-input-error for="global_header" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="global_footer" value="{{ __('Global Footer') }}" />
            <textarea id="global_footer" class="form-input border-gray-300 rounded-md shadow-sm mt-4 block w-full resize-y border" placeholder="Enter your HTML Code here" wire:model.defer="state.global.footer"></textarea>
            <x-jet-input-error for="global_footer" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>