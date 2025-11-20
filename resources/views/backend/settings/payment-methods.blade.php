<x-jet-form-section submit="save">
    <x-slot name="title">
        {{ __('Payment Methods') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can setup Payment Methods for your tSMS for users can add funds to their wallet.') }}
    </x-slot>
    
    <x-slot name="form">
        <div class="col-span-6 {{ $view != 'methods' ? 'hidden' : '' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($methods as $method)
                <div class="flex flex-col items-center justify-center border-2 rounded-lg cursor-pointer {{ $method->is_active ? 'border-green-700 hover:bg-green-50' : 'hover:bg-gray-50' }}" onclick="setTimeout(() => { document.querySelector('#enable_{{ $method->name }}').scrollIntoView({behavior: 'smooth'}) }, 500)" wire:click="$set('view', '{{ $method->name }}')">
                    <img class="max-h-16 p-4" src="{{ asset('images/payment-methods/'.$method->name.'.png') }}" alt="{{ $method->name }}">
                    @if($method->is_active)
                    <small class="mb-2 font-bold text-green-700">{{ __('Active') }}</small>
                    @else
                    <small class="mb-2 font-bold text-gray-300">{{ __('Inactive') }}</small>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-span-6 {{ $view != 'methods' ? '' : 'hidden' }}">
            <div class="flex justify-between items-center">
                <button type="button" class="flex gap-3 items-center border-2 border-gray-200 text-gray-500 px-4 py-2 rounded-lg text-xs" wire:click="$set('view', 'methods')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ __('Back') }}</span>
                </button>
                @if($view == 'coinpayments')
                <button type="button" class="flex gap-3 items-center border-2 border-tertiary text-white bg-tertiary px-4 py-2 rounded-lg text-xs" wire:click="updateCoinPaymentsCoins">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4" wire:loading.class="animate-spin" wire:target="updateCoinPaymentsCoins">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>{{ __('Update Coins Data') }}</span>
                </button>
                @endif
            </div>
            @if($view == 'stripe')
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div>
                    <label for="enable_stripe" class="flex cursor-pointer">
                        <div class="text-sm">{{ __('Enable Stripe') }}</div>
                        <div class="ml-3 relative">
                            <input type="checkbox" id="enable_stripe" class="sr-only" wire:model.defer="stripe.is_active">
                            <div class="dot-bg block bg-gray-600 w-8 h-5 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <div>
                    <x-jet-label for="stripe_key" value="{{ __('API Key') }}" />
                    <x-jet-input id="stripe_key" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. rk_1234abcdef OR sk_1234abcdef') }}" wire:model.defer="stripe.key" />
                    <x-jet-input-error for="stripe.key" class="mt-2" />
                    <small class="mt-2 block">{{ __('How to generate API Key?') }}</small>
                </div>
            </div>
            @elseif($view == 'razorpay')
            @if(strtolower(config('app.settings.currency.code')) != 'inr')
            <div class="bg-yellow-50 rounded-lg p-4 mt-5">
                <div class="flex justify-start items-start space-x-5">
                    <div class="text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">{{ __('RazorPay only supported with INR currency.') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div>
                    <label for="enable_razorpay" class="flex cursor-pointer">
                        <div class="text-sm">{{ __('Enable Razorpay') }}</div>
                        <div class="ml-3 relative">
                            <input type="checkbox" id="enable_razorpay" class="sr-only" wire:model.defer ="razorpay.is_active">
                            <div class="dot-bg block bg-gray-600 w-8 h-5 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <div>
                    <x-jet-label for="razorpay_key_id" value="{{ __('Key ID') }}" />
                    <x-jet-input id="razorpay_key_id" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. rzp_abcde123') }}" wire:model.defer="razorpay.key_id" />
                    <x-jet-input-error for="razorpay.key_id" class="mt-2" />
                </div>
                <div>
                    <x-jet-label for="razorpay_key_secret" value="{{ __('Key Secret') }}" />
                    <x-jet-input id="razorpay_key_secret" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. Cr12345678abce') }}" wire:model.defer="razorpay.key_secret" />
                    <x-jet-input-error for="razorpay.key_secret" class="mt-2" />
                </div>
            </div>
            @elseif($view == 'paypal')
            @if(strtolower(config('app.settings.currency.code')) == 'inr')
            <div class="bg-yellow-50 rounded-lg p-4 mt-5">
                <div class="flex justify-start items-start space-x-5">
                    <div class="text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">{{ __('PayPal will not work with INR currency.') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div>
                    <label for="enable_paypal" class="flex cursor-pointer">
                        <div class="text-sm">{{ __('Enable PayPal') }}</div>
                        <div class="ml-3 relative">
                            <input type="checkbox" id="enable_paypal" class="sr-only" wire:model.defer ="paypal.is_active">
                            <div class="dot-bg block bg-gray-600 w-8 h-5 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <div>
                    <x-jet-label for="paypal_client_id" value="{{ __('Client ID') }}" />
                    <x-jet-input id="paypal_client_id" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. KUzsueds') }}" wire:model.defer="paypal.client_id" />
                    <x-jet-input-error for="paypal.client_id" class="mt-2" />
                </div>
                <div>
                    <x-jet-label for="razorpay_client_secret" value="{{ __('Client Secret') }}" />
                    <x-jet-input id="razorpay_client_secret" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. BiIdCh5ezE') }}" wire:model.defer="paypal.client_secret" />
                    <x-jet-input-error for="paypal.client_secret" class="mt-2" />
                </div>
            </div>
            @elseif($view == 'coinpayments')
            @if(\App\Services\Util::showPaymentMethod($view) == false)
            <div class="bg-yellow-50 rounded-lg p-4 mt-5">
                <div class="flex justify-start items-start space-x-5">
                    <div class="text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">{{ __('CoinPayments does not support') }} {{ strtoupper(config('app.settings.currency.code')) }} {{ __('currency') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div>
                    <label for="enable_coinpayments" class="flex cursor-pointer">
                        <div class="text-sm">{{ __('Enable CoinPayments') }}</div>
                        <div class="ml-3 relative">
                            <input type="checkbox" id="enable_coinpayments" class="sr-only" wire:model.defer ="coinpayments.is_active">
                            <div class="dot-bg block bg-gray-600 w-8 h-5 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <div>
                    <x-jet-label for="coinpayments_public_key" value="{{ __('Public Key') }}" />
                    <x-jet-input id="coinpayments_public_key" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. 3d65147e5d3a3') }}" wire:model.defer="coinpayments.public_key" />
                    <x-jet-input-error for="coinpayments.public_key" class="mt-2" />
                </div>
                <div>
                    <x-jet-label for="coinpayments_private_key" value="{{ __('Private Key') }}" />
                    <x-jet-input id="coinpayments_private_key" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. b67de6e63Bc34') }}" wire:model.defer="coinpayments.private_key" />
                    <x-jet-input-error for="coinpayments.private_key" class="mt-2" />
                </div>
            </div>
            @elseif($view == 'paystack')
            @if(\App\Services\Util::showPaymentMethod($view) == false)
            <div class="bg-yellow-50 rounded-lg p-4 mt-5">
                <div class="flex justify-start items-start space-x-5">
                    <div class="text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">{{ __('Paystack does not support') }} {{ strtoupper(config('app.settings.currency.code')) }} {{ __('currency') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 gap-6 mt-6">
                <div>
                    <label for="enable_paystack" class="flex cursor-pointer">
                        <div class="text-sm">{{ __('Enable Paystack') }}</div>
                        <div class="ml-3 relative">
                            <input type="checkbox" id="enable_paystack" class="sr-only" wire:model.defer ="paystack.is_active">
                            <div class="dot-bg block bg-gray-600 w-8 h-5 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <div>
                    <x-jet-label for="paystack_public_key" value="{{ __('Public Key') }}" />
                    <x-jet-input id="paystack_public_key" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. pk_test_3d65147e5d3a3') }}" wire:model.defer="paystack.public_key" />
                    <x-jet-input-error for="paystack.public_key" class="mt-2" />
                </div>
                <div>
                    <x-jet-label for="paystack_secret_key" value="{{ __('Secret Key') }}" />
                    <x-jet-input id="paystack_secret_key" type="password" class="mt-3 block w-full" placeholder="{{ __('eg. sk_test_b67de6e63Bc34') }}" wire:model.defer="paystack.secret_key" />
                    <x-jet-input-error for="paystack.secret_key" class="mt-2" />
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    @if($view != 'methods')
    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>
        <x-jet-button class="paymentsave">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
    @endif
</x-jet-form-section>