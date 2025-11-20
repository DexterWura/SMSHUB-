<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Configuration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('tSMS specific settings which are applied on the App functionality.') }}
    </x-slot>

    <x-slot name="form">
        <div x-data="{ show: false }" class="col-span-6 sm:col-span-4">
            <x-jet-label for="auth_key" value="{{ __('Authorization Key') }}" />
            <div class="relative">
                <x-jet-input id="auth_key" x-bind:type="show ? 'text' : 'password'" class="mt-1 block w-full" autocomplete="new-password" wire:model.defer="state.auth_key" />
                <div x-on:click="show = !show" x-text="show ? 'HIDE' : 'SHOW'" class="cursor-pointer absolute inset-y-0 right-0 flex items-center px-5 text-xs"></div>
            </div>
        </div>
        <div class="hidden col-span-6 sm:col-span-4">
            <x-jet-label for="numbers_per_page" value="{{ __('Numbers Per Page') }}" />
            <x-jet-input id="numbers_per_page" type="number" class="mt-1 block w-full" wire:model.defer="state.numbers_per_page" />
            <x-jet-input-error for="state.numbers_per_page" class="mt-2" />
            <small>{{ __('You can set the amount of numbers shown on a page. Enter -1 to show all.') }}</small>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="messages_per_page" value="{{ __('Messages Per Page') }}" />
            <x-jet-input id="messages_per_page" type="number" class="mt-1 block w-full" wire:model.defer="state.messages_per_page" />
            <x-jet-input-error for="state.messages_per_page" class="mt-2" />
            <small>{{ __('You can set the amount of messages shown on a page.') }}</small>
        </div>
        <div class="col-span-6 hidden">
            <x-jet-label for="widget_allowed_domains" value="{{ __('Domains Allowed to load tSMS Widget') }}" />
            <textarea id="widget_allowed_domains" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full resize-y border" placeholder="Enter comma separated values" wire:model.defer="state.widget_allowed_domains"></textarea>
            <x-jet-input-error for="state.widget_allowed_domains" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-jet-label for="currency_symbol" value="{{ __('Currency Symbol') }}" />
                    <x-jet-input id="currency_symbol" type="text" class="mt-1 block w-full" wire:model.defer="state.currency.symbol" />
                    <x-jet-input-error for="state.currency.symbol" class="mt-2" />
                </div>
                <div>
                    <x-jet-label for="currency_code" value="{{ __('Currency Code') }}" />
                    <x-jet-input id="currency_code" type="text" class="mt-1 block w-full" wire:model.defer="state.currency.code" />
                    <x-jet-input-error for="state.currency.code" class="mt-2" />
                </div>
            </div>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="min_recharge" value="{{ __('Minimum Recharge Amount') }}" />
            <div class="mt-1 flex">
                <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                    {{ $state['currency']['symbol'] }}
                </span>
                <x-jet-input id="min_recharge" type="number" class="block w-full rounded-l-none" wire:model.defer="state.min_recharge" />
            </div>
            <x-jet-input-error for="state.min_recharge" class="mt-2" />
            <small>{{ __('Users need to atleast load this amount while recharging wallet.') }}</small>
        </div>
        <div class="col-span-6">
            <x-jet-label for="blocked_keywords" value="{{ __('Blocked Keywords') }}" />
            <textarea id="blocked_keywords" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full resize-y border" placeholder="Enter comma separated values" wire:model.defer="state.blocked_keywords"></textarea>
            <x-jet-input-error for="state.blocked_keywords" class="mt-2" />
            <small>{{ __('Messages that contain this keyword(s) are not shown to end users.') }}</small>
        </div>
        <div x-data="{ show: false }" class="col-span-6 sm:col-span-4">
            <x-jet-label for="cron_password" value="{{ __('CRON Password') }}" />
            <div class="relative">
                <x-jet-input id="cron_password" x-bind:type="show ? 'text' : 'password'" class="mt-1 block w-full" autocomplete="new-password" wire:model.defer="state.cron_password" />
                <div x-on:click="show = !show" x-text="show ? 'HIDE' : 'SHOW'" class="cursor-pointer absolute inset-y-0 right-0 flex items-center px-5 text-xs"></div>
            </div>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="captcha" value="{{ __('Captcha') }}" />
            <div class="relative">
                <select class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model="state.captcha">
                    <option value="off">{{ __('Disabled') }}</option>
                    <option value="recaptcha2">reCaptcha v2</option>
                    <option value="recaptcha3">reCaptcha v3</option>
                    <option value="hcaptcha">hCaptcha</option>
                </select>
            </div>
            @if($state['captcha'] == 'recaptcha2')
            <div class="mt-6">
                <div>
                    <x-jet-label for="recaptcha2_site_key" value="{{ __('Site Key') }}" />
                    <x-jet-input id="recaptcha2_site_key" type="text" class="mt-1 block w-full" wire:model.defer="state.recaptcha2.site_key" />
                    <x-jet-input-error for="state.recaptcha2.site_key" class="mt-1 mb-2" />
                </div>
                <div class="mt-2">
                    <x-jet-label for="recaptcha2_secret_key" value="{{ __('Secret Key') }}" />
                    <x-jet-input id="recaptcha2_secret_key" type="text" class="mt-1 block w-full" wire:model.defer="state.recaptcha2.secret_key" />
                    <x-jet-input-error for="state.recaptcha2.secret_key" class="mt-1 mb-2" />
                </div>
            </div>
            @elseif($state['captcha'] == 'recaptcha3')
            <div class="mt-6">
                <div>
                    <x-jet-label for="recaptcha3_site_key" value="{{ __('Site Key') }}" />
                    <x-jet-input id="recaptcha3_site_key" type="text" class="mt-1 block w-full" wire:model.defer="state.recaptcha3.site_key" />
                    <x-jet-input-error for="state.recaptcha3.site_key" class="mt-1 mb-2" />
                </div>
                <div class="mt-2">
                    <x-jet-label for="recaptcha3_secret_key" value="{{ __('Secret Key') }}" />
                    <x-jet-input id="recaptcha3_secret_key" type="text" class="mt-1 block w-full" wire:model.defer="state.recaptcha3.secret_key" />
                    <x-jet-input-error for="state.recaptcha3.secret_key" class="mt-1 mb-2" />
                </div>
            </div>
            @elseif($state['captcha'] == 'hcaptcha')
            <div class="mt-6">
                <div>
                    <x-jet-label for="hcaptcha_site_key" value="{{ __('Site Key') }}" />
                    <x-jet-input id="hcaptcha_site_key" type="text" class="mt-1 block w-full" wire:model.defer="state.hcaptcha.site_key" />
                    <x-jet-input-error for="state.hcaptcha.site_key" class="mt-1 mb-2" />
                </div>
                <div class="mt-2">
                    <x-jet-label for="hcaptcha_secret_key" value="{{ __('Secret Key') }}" />
                    <x-jet-input id="hcaptcha_secret_key" type="text" class="mt-1 block w-full" wire:model.defer="state.hcaptcha.secret_key" />
                    <x-jet-input-error for="state.hcaptcha.secret_key" class="mt-1 mb-2" />
                </div>
            </div>
            @endif
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