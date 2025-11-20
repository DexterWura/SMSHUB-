@section('header')
{!! $page->header !!}
<title>{{ $page->title }} - {{ config('app.settings.name') }}</title>
@endsection 

<main class="my-10 space-y-10">
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-primary">{{ __($page->title) }}</h1>
        <div class="border-b border-dashed border-primary w-24"></div>
        <p>
            {!! __($page->content) !!}
        </p>
    </div>
    @if($selected['number'] == null)
    <div class="space-y-6">
        <div>
            <h4 class="text-xs uppercase text-gray-400 font-bold">{{ __('Step 1 of 3') }}</h4>
            <h2 class="text-lg font-bold text-primary">{{ __('Choose a Number') }}</h2>
        </div>
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-end sm:space-x-5">
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Country') }}</label>
                <select data-filter="number" name="country" class="text-sm mt-1 py-2 block border border-gray-200 rounded-lg">
                    <option value="null" disabled selected>{{ __('Select Country') }}</option>
                    @foreach($filters['number']['country'] as $country)
                    <option value="{{ $country }}">{{ \App\Services\Util::getCountryNameFromCode($country) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Type') }}</label>
                <div class="flex space-x-3">
                    <div class="flex items-center mt-1 py-2">
                        <input data-filter="number" value="3" id="type_3" name="type" type="radio" class="focus:ring-gray-600 text-gray-600 h-4 w-4 border-gray-300">
                        <label for="type_3" class="ml-2 block text-sm font-medium text-gray-700">{{ __($types[3]) }}</label>
                    </div>
                    <div class="flex items-center mt-1 py-2">
                        <input data-filter="number" value="4" id="type_4" name="type" type="radio" class="focus:ring-gray-600 text-gray-600 h-4 w-4 border-gray-300">
                        <label for="type_4" class="ml-2 block text-sm font-medium text-gray-700">{{ __($types[4]) }}</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Sort By') }}</label>
                <select data-filter="number-sort" name="sort_by" class="text-sm mt-1 py-2 block border border-gray-200 rounded-lg">
                    <option value="oldest" selected>{{ __('Oldest') }}</option>
                    <option value="latest">{{ __('Latest') }}</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button data-filter="number" type="button" class="bg-primary text-white rounded px-3 py-2 text-sm filter">{{ __('Filter') }}</button>
                <button data-filter="number" type="button" class="bg-secondary text-white rounded px-3 py-2 text-sm clear">{{ __('Clear') }}</button>
            </div>
        </div>
        <section id="number" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($numbers as $number)
            <div data-country="{{ $number->country }}" data-type="{{ $number->type }}" class="border-2 flex flex-col justify-between border-primary rounded-lg">
                <div class="flex justify-between p-5">
                    <div class="space-y-1">
                        <h3 class="text-primary font-semibold">+{{ $number->number }}</h3>
                        <div class="flex items-center space-x-1">
                            <span class="{{ $bg[$number->type] }} {{ $text[$number->type] }} px-2 py-1 text-xs font-bold rounded-full">{{ __($types[$number->type]) }}</span>
                            <div class="has-tooltip relative flex justify-center cursor-help">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip">
                                    @if($number->type == 3)
                                    {{ __('Multiple users can rent out same number') }}
                                    @else
                                    {{ __('Dedicated access to this number') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img src="https://flagcdn.com/h40/{{ strtolower($number->country) }}.png" alt="img">
                    </div>
                </div>
                @if(trim($number->meta))
                <h5 class="p-5 pt-0 text-sm text-gray-400">{{ $number->meta }}</h5>
                @endif
                <button class="w-full block text-center bg-primary text-white py-2 text-sm rounded-b" wire:click="setNumber({{ $number->id }})">
                    {{ __('Rent') }}
                </button>
            </div>
            @endforeach
        </section>
    </div>
    @elseif($selected['number'] && $selected['plan'] == null)
    <div class="space-y-6">
        <div>
            <h4 class="text-xs uppercase text-gray-400 font-bold">{{ __('Step 2 of 3') }}</h4>
            <h2 class="text-lg font-bold text-primary">{{ __('Choose a Plan') }}</h2>
        </div>
        @if(count($plans))
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($plans as $plan)
            <div class="border-2 border-primary rounded-lg shadow">
                <div class="flex justify-between items-center p-5">
                    <div class="space-y-3">
                        <h4 class="cursor-pointer">{{ $plan->name }}</h4>
                        <div class="flex items-center mt-3 space-x-2 md:space-x-5 text-xs md:text-sm text-gray-400 font-semibold">
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
                </div>
                <button class="w-full block text-center bg-primary text-white py-2 text-sm rounded-b" wire:click="setPlan({{ $plan->id }})">
                    {{ __('Choose') }}
                </button>
            </div>
            @endforeach
        </section>
        @else
        <div class="text-sm text-gray-500 font-bold">{{ __('No Plans Available') }}</div>
        @endif
        <button class="border-2 border-primary text-primary font-bold py-2 px-5 text-sm rounded-lg flex space-x-2" wire:click="backTo('numbers')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="pr-2">{{ __('Back') }}</span>
        </button>
    </div>
    @elseif($success == null)
    <div class="space-y-6">
        <div>
            <h4 class="text-xs uppercase text-gray-400 font-bold">{{ __('Step 3 of 3') }}</h4>
            <h2 class="text-lg font-bold text-primary">{{ __('Confirm your Purchase') }}</h2>
        </div>
        @if($error)
        <div class="bg-red-50 rounded-lg p-4">
            <div class="flex justify-start items-start space-x-5">
                <div class="text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="space-y-3">
                    <p class="text-sm text-red-800 font-bold">{{ __('There were errors while submitting your order.') }}</p>
                    <ul class="list-disc list-inside text-red-800 text-sm">
                        <li>{{ $error }}</li>
                    </ul>
                    <div class="flex space-x-3 pt-2">
                        @if(str_contains($error, 'balance'))
                        <a href="{{ route('wallet') }}" class="text-sm text-red-800 font-medium">{{ __('Add Funds') }}</a>
                        @endif
                        <a href="#" class="text-sm text-red-800 font-medium" wire:click="dismissError">{{ __('Dismiss') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <section class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="flex flex-col justify-between rounded-lg {{ $bg[$selected['number']['type']] }} {{ $text[$selected['number']['type']] }}">
                <div class="flex justify-between items-center p-5">
                    <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:space-x-20">
                        <div class="space-y-1">
                            <h4 class="text-xs md:text-sm font-bold">{{ __('Number') }}</h4>
                            <h3 class="md:text-xl font-semibold">+{{ $selected['number']['number'] }}</h3>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold">{{ __('Country') }}</h4>
                            <h3 class="md:text-xl font-semibold">{{ $this->getCountryName($selected['number']['country']) }}</h3>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-2xl font-bold">{{ __($types[$selected['number']['type']]) }}</h4>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-between bg-primary rounded-lg text-white">
                <div class="flex justify-between items-center p-5">
                    <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:space-x-20">
                        <div class="space-y-1">
                            <h4 class="text-xs md:text-sm font-bold">{{ __('Plan') }}</h4>
                            <h3 class="md:text-xl font-semibold">{{ $selected['plan']['name'] }}</h3>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold">{{ __('Validity') }}</h4>
                            <h3 class="md:text-xl font-semibold">{{ $selected['plan']['validity'] }} {{ __(ucfirst($selected['plan']['validity_type']) . ( $selected['plan']['validity'] > 1 ? 's' : '')) }}</h3>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl md:text-3xl font-bold">{{ config('app.settings.currency.symbol') }}{{ $selected['plan']['price'] }}</h4>
                    </div>
                </div>
            </div>
            <button class="flex space-x-3 md:col-span-2 justify-center py-3 bg-primary text-white font-bold rounded-lg" wire:click="submit">
                <span>{{ __('Submit Order') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </button>
        </section>
        <button class="border-2 border-primary text-primary font-bold py-2 px-5 text-sm rounded-lg flex space-x-2" wire:click="backTo('plans')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="pr-2">{{ __('Back') }}</span>
        </button>
    </div>
    @elseif($success)
    <div class="space-y-6">
        <div>
            <h4 class="text-xs uppercase text-gray-400 font-bold">{{ __('All Done') }}</h4>
            <h2 class="text-lg font-bold text-primary">{{ __('Order Completed') }}</h2>
        </div>
        <div class="bg-green-50 w-full flex justify-between items-center rounded-lg p-4">
            <div class="flex justify-start space-x-5">
                <div class="text-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="space-y-3">
                    <p class="text-sm text-green-800 font-bold">{{ __('Success!') }}</p>
                    <p class="text-green-800">{{ $success }}</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('redirect', e => {
            setTimeout(() => {
                window.location.replace("{{ route('number', $selected['number']['number']) }}");
            }, 5000);
        })
    </script>
    @endif
</main>