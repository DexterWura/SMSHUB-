<main class="my-10 space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <div class="space-y-6">
                <h1 class="text-2xl font-bold text-primary">{{ __('My Wallet') }}</h1>
                <div class="border-b border-dashed border-primary w-24"></div>
                <div class="pt-5">
                    <h3 class="inline-block uppercase font-bold p-3 text-xl md:text-3xl text-white bg-primary">{{ config('app.settings.currency.code') }}</h3>
                    <h2 class="mt-3 font-bold text-5xl lg:text-6xl xl:text-7xl text-primary">{{ config('app.settings.currency.symbol') }}{{ number_format(auth()->user()->wallet, 2) }}</h2>
                    <h5 class="font-medium mt-10">{{ __('You\'ve done a total of') }} <span class="font-bold">{{ $total }}</span> {{ __('funds transactions on') }} {{ config('app.settings.name') }}</h5>
                    <h6 class="text-sm text-gray-400">{{ __('You can view your transaction history below.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <div class="space-y-6">
                <h1 class="text-2xl font-bold text-primary">{{ __('Add Funds to Wallet') }}</h1>
                <div class="border-b border-dashed border-primary w-24"></div>
                <p>
                    {{ __('Add more funds to wallet so you that can be used to rent out numbers') }}
                </p>
            </div>
            <form class="grid grid-cols-1 gap-3" wire:submit.prevent="submit">
                @if($error)
                <div class="bg-red-50 w-full flex justify-between items-center rounded-lg p-4">
                    <div class="flex justify-start items-center space-x-5">
                        <div class="text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm text-red-800 font-medium">{{ __('Error!') }} {{ $error }}</p>
                    </div>
                </div>
                @endif
                <label class="mt-3 text-gray-600 font-bold text-sm">{{ __('Enter the Amount') }}</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                        {{ config('app.settings.currency.symbol') }}
                    </span>
                    <x-jet-input id="amount" type="number" max="999999" min="{{ config('app.settings.min_recharge', 1) }}" class="block w-full rounded-l-none" wire:model.defer="amount" placeholder="Enter the Amount you want Add" required/>
                </div>
                <x-jet-input-error for="amount" />
                <label class="mt-3 text-gray-600 font-bold text-sm">{{ __('Select Payment Gateway') }}</label>
                <div class="flex items-center gap-3">
                    @foreach($methods as $method)
                    @if(\App\Services\Util::showPaymentMethod($method->name))
                    <div class="flex items-center border-2 rounded-lg {{ $method->name == $selected ? 'border-primary' : '' }}">
                        <input id="method_{{ $method->name }}" value="{{ $method->name }}" name="method" type="radio" class="hidden" wire:model="selected">
                        <label for="method_{{ $method->name }}" class="block text-sm font-medium text-gray-700">
                            <img class="max-h-16 p-4 cursor-pointer" src="{{ asset('images/payment-methods/'.$method->name.'.png') }}" alt="{{ $method->name }}">
                        </label>
                    </div>
                    @endif
                    @endforeach
                    @if(count($methods) == 0)
                    <small class="text-gray-400">{{ __('No Payment Methods Available') }}</small>
                    @endif
                </div>
                <x-jet-input-error for="selected" class="mb-2" />
                @if($selected == 'coinpayments')
                <label class="mt-3 text-gray-600 font-bold text-sm">{{ __('Select the Crypto Currency') }}</label>
                <div class="relative">
                    <select id="crypto" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model="crypto">
                        <option selected disabled value="empty">{{ __('Select a Currency') }}</option>
                        @foreach(json_decode($method->metadata)->coins as $coin)
                        <option value="{{ $coin }}">{{ strtoupper($coin) }}</option>
                        @endforeach
                    </select>
                </div>
                <x-jet-input-error for="crypto" class="mt-2" />
                @endif
                <button type="submit" class="bg-primary text-white rounded-lg py-3 border-0 flex items-center justify-center gap-3 disabled:bg-gray-400">
                    <svg wire:loading wire:target="submit" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Add Funds') }}
                </button>
            </form>
        </div>
    </div>
    <div>
        <div class="space-y-6">
            <h1 class="text-2xl font-bold text-primary">{{ __('List of Transactions') }}</h1>
            <div class="border-b border-dashed border-primary w-24"></div>
            <div class="bg-gray-400 text-white shadow rounded-lg overflow-hidden">
                @foreach($transactions as $transaction)
                <div class="border-b py-4 px-5 grid grid-cols-3 md:grid-cols-4">
                    <div class="hidden md:block">
                        <label class="text-xs font-medium text-gray-100">{{ __('Transaction #') }}</label>
                        <div class="text-lg font-bold">{{ $transaction->id }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-100">{{ __('Payment Method') }}</label>
                        <div>
                            <span class="uppercase text-xs font-bold bg-gray-800 text-white rounded px-2 py-1">{{ $transaction->payment_method }}</span>
                        </div>
                    </div>
                    @php
                    $data = json_decode($transaction->data);
                    @endphp
                    <div>
                        <label class="text-xs font-medium text-gray-100">{{ __('Amount') }}</label>
                        @if($transaction->payment_method == 'stripe' && isset($data->amount_total))
                        <div class="text-lg font-bold">{{ config('app.settings.currency.symbol') }}{{ $data->amount_total / 100  }}</div>
                        @elseif($transaction->payment_method == 'razorpay' && isset($data->amount))
                        <div class="text-lg font-bold">{{ config('app.settings.currency.symbol') }}{{ $data->amount / 100  }}</div>
                        @elseif($transaction->payment_method == 'paypal' && isset($data->amount))
                        <div class="text-lg font-bold">{{ config('app.settings.currency.symbol') }}{{ $data->amount  }}</div>
                        @elseif($transaction->payment_method == 'coinpayments' && isset($data->amount))
                        <div class="text-lg font-bold">{{ config('app.settings.currency.symbol') }}{{ $data->amount  }}</div>
                        @elseif($transaction->payment_method == 'paynow' && isset($data->amount))
                        <div class="text-lg font-bold">{{ config('app.settings.currency.symbol') }}{{ $data->amount  }}</div>
                        @elseif($transaction->payment_method == 'paystack' && isset($data->data->amount))
                        <div class="text-lg font-bold">{{ config('app.settings.currency.symbol') }}{{ $data->data->amount / 100  }}</div>
                        @else
                        <div class="text-lg font-bold">-</div>
                        @endif
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-100">{{ __('Status') }}</label>
                        @if($transaction->status == 0)
                        <div class="flex gap-2">
                            <span class="text-xs font-bold bg-yellow-300 text-gray-800 rounded px-2 py-1">{{ __('Processing') }}</span>
                            @if($transaction->payment_method == 'coinpayments' && gettype($data->result) == 'object')
                            <a href="{{ $data->result->checkout_url }}" target="_blank" class="text-xs font-bold bg-black text-white rounded px-2 py-1 flex items-center gap-1">
                                {{ __('View') }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3.5" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            @endif
                        </div>
                        @elseif($transaction->status == 1)
                        <div>
                            <span class="text-xs font-bold bg-green-300 text-gray-800 rounded px-2 py-1">{{ __('Completed') }}</span>
                        </div>
                        @elseif($transaction->status == 2)
                        <div>
                            <span class="text-xs font-bold bg-red-300 text-gray-800 rounded px-2 py-1">{{ __('Cancelled') }}</span>
                        </div>
                        @elseif($transaction->status == 3)
                        <div>
                            <span class="text-xs font-bold bg-pink-300 text-gray-800 rounded px-2 py-1">{{ __('Fraud') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            {{ $transactions->links() }}
        </div>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @if($selected == 'razorpay' && $razorpay)
    <form name="razorpayform" action="{{ route('wallet.process', ['id' => (json_decode($razorpay)->transaction_id), 'status' => 'process']) }}" method="POST">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
    </form>
    <script>
    var options = {!! $razorpay !!};
    console.log(options)
    options.handler = function (response){
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.razorpayform.submit();
    };
    options.modal = {
        ondismiss: function() {
            window.Livewire.emit('cancelOrder', options.transaction_id)
        },
        escape: true,
        backdropclose: false
    };
    var rzp = new Razorpay(options);
    window.addEventListener('razorpay-process', e => {
        rzp.open();
    })
    </script>
    @endif
</main>