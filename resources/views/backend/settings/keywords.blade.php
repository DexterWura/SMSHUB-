<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Keywords') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can customize your Keywords Configuration in case your SMS provider do not provide custom keywords for SMS Forwarding.') }}
    </x-slot>
    
    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="to" value="{{ __('Number') }}" />
            <x-jet-input id="to" type="text" class="mt-1 block w-full" wire:model.defer="state.keywords.to" />
            <x-jet-input-error for="state.keywords.to" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="from" value="{{ __('Sender') }}" />
            <x-jet-input id="from" type="text" class="mt-1 block w-full" wire:model.defer="state.keywords.from" />
            <x-jet-input-error for="state.keywords.from" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="msg" value="{{ __('Message') }}" />
            <x-jet-input id="msg" type="text" class="mt-1 block w-full" wire:model.defer="state.keywords.msg" />
            <x-jet-input-error for="state.keywords.msg" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="uuid" value="{{ __('Message UUID') }}" />
            <x-jet-input id="uuid" type="text" class="mt-1 block w-full" wire:model.defer="state.keywords.uuid" />
            <x-jet-input-error for="state.keywords.uuid" class="mt-2" />
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