<main class="my-10 space-y-20">
    <div class="space-y-6">
        <div class="space-y-6">
            <h1 class="text-2xl font-bold text-primary">{{ $sections[0]->title }}</h1>
            <div class="border-b border-dashed border-primary w-24"></div>
            <p>
                {!! $sections[0]->content !!}
            </p>
        </div>
        @if(config('app.settings.show_country_list'))
        <div id="country-list" class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-end sm:space-x-5">
            @foreach($filters['number']['country'] as $country)
            <div class="border-2 border-secondary rounded-lg">
                <div class="p-5">
                    <img src="https://flagcdn.com/h120/{{ strtolower($country) }}.png" alt="img">
                    <p class="font-bold text-center pt-5">{{ \App\Services\Util::getCountryNameFromCode($country) }}</p>
                </div>
                <a class="w-full block text-center bg-secondary text-white py-2 text-sm rounded-b" href="#/country/{{ strtolower($country) }}">
                    {{ __('View Numbers') }}
                </a>
            </div>
            @endforeach
        </div>
        @endif
        <div id="number-filters" class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-end sm:space-x-5 {{ config('app.settings.show_country_list') ? 'hidden' : '' }}">
            @if(config('app.settings.show_country_list'))
            <button data-action="show_country_list" type="button" class="bg-primary text-white rounded px-3 py-2 text-sm"><i class="fas fa-arrow-left"></i><span class="ml-2">{{ __('Back to Country List') }}</span></button>
            @endif
            <div class="flex flex-col {{ config('app.settings.show_country_list') ? 'hidden' : '' }}">
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
                        <input data-filter="number" value="0" id="type_0" name="type" type="radio" class="focus:ring-gray-600 text-gray-600 h-4 w-4 border-gray-300">
                        <label for="type_0" class="ml-2 block text-sm font-medium text-gray-700">{{ __($types[0]) }}</label>
                    </div>
                    <div class="flex items-center mt-1 py-2">
                        <input data-filter="number" value="1" id="type_1" name="type" type="radio" class="focus:ring-gray-600 text-gray-600 h-4 w-4 border-gray-300">
                        <label for="type_1" class="ml-2 block text-sm font-medium text-gray-700">{{ __($types[1]) }}</label>
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
        @if(config('app.settings.ads.one'))
        <div class="max-w-full ads-one">{!! config('app.settings.ads.one') !!}</div>
        @endif
        <section id="number" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5 {{ config('app.settings.show_country_list') ? 'hidden' : '' }}">
            @foreach($numbers as $number)
            <div data-country="{{ $number->country }}" data-type="{{ $number->type }}" class="border-2 flex flex-col justify-between {{ ($number->type == 0 || $user) ? 'border-secondary' : 'border-gray-400' }} rounded-lg">
                <div class="flex justify-between p-5">
                    <div class="space-y-1">
                        <h4 class="font-semibold">+{{ $number->number }}</h4>
                        <h5 class="text-sm text-gray-400">{{ $number->meta }}</h5>
                    </div>
                    <div>
                        <img src="https://flagcdn.com/h40/{{ strtolower($number->country) }}.png" alt="img">
                    </div>
                </div>
                @if($number->type == 0 || $user)
                <a class="w-full block text-center bg-secondary text-white py-2 text-sm rounded-b" href="{{ route('number', $number->number) }}">
                    {{ __('View Messages') }}
                </a>
                @else
                <a class="w-full block text-center bg-gray-400 text-white py-2 text-sm cursor-not-allowed rounded-b" href="{{ route('number', $number->number) }}">
                    {{ __('Register to View Messages') }}
                </a>
                @endif
            </div>
            @endforeach
        </section>
        @if(config('app.settings.ads.two'))
        <div class="max-w-full ads-two">{!! config('app.settings.ads.two') !!}</div>
        @endif
    </div>
    @if($assigned && count($assigned) > 0)
    <div class="space-y-6">
        <div class="space-y-6">
            <h1 class="text-2xl font-bold text-primary">{{ __('Private Numbers') }}</h1>
            <div class="border-b border-dashed border-primary w-24"></div>
        </div>
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-end sm:space-x-5">
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Country') }}</label>
                <select data-filter="private" name="country" class="text-sm mt-1 py-2 block border border-gray-200 rounded-lg">
                    <option value="null" disabled selected>{{ __('Select Country') }}</option>
                    @foreach($filters['private']['country'] as $country)
                    <option value="{{ $country }}">{{ \App\Services\Util::getCountryNameFromCode($country) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Sort By') }}</label>
                <select data-filter="private-sort" name="sort_by" class="text-sm mt-1 py-2 block border border-gray-200 rounded-lg">
                    <option value="oldest" selected>{{ __('Oldest') }}</option>
                    <option value="latest">{{ __('Latest') }}</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button data-filter="private" type="button" class="bg-primary text-white rounded px-3 py-2 text-sm filter">{{ __('Filter') }}</button>
                <button data-filter="private" type="button" class="bg-secondary text-white rounded px-3 py-2 text-sm clear">{{ __('Clear') }}</button>
            </div>
        </div>
        <section id="private" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5">
            @foreach($assigned as $assign)
            <div data-country="{{ $assign->number->country }}" data-type="3" class="border-2 flex flex-col justify-between border-primary rounded-lg">
                <div class="flex justify-between p-5">
                    <div class="space-y-1">
                        <h4 class="font-semibold">+{{ $assign->number->number }}</h4>
                        <h5 class="text-sm text-gray-400">{{ $assign->number->meta }}</h5>
                    </div>
                    <div>
                        <img src="https://flagcdn.com/h40/{{ strtolower($assign->number->country) }}.png" alt="img">
                    </div>
                </div>
                <a class="w-full block text-center bg-primary text-white py-2 text-sm rounded-b" href="{{ route('number', $assign->number->number) }}">
                    {{ __('View Messages') }}
                </a>
            </div>
            @endforeach
        </section>
    </div>
    @endif
    @if($orders && count($orders) > 0)
    <div class="space-y-6">
        <div class="space-y-6">
            <h1 class="text-2xl font-bold text-primary">{{ __('Rented Numbers') }}</h1>
            <div class="border-b border-dashed border-primary w-24"></div>
        </div>
        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-end sm:space-x-5">
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Country') }}</label>
                <select data-filter="rented" name="country" class="text-sm mt-1 py-2 block border border-gray-200 rounded-lg">
                    <option value="null" disabled selected>{{ __('Select Country') }}</option>
                    @foreach($filters['rented']['country'] as $country)
                    <option value="{{ $country }}">{{ \App\Services\Util::getCountryNameFromCode($country) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Type') }}</label>
                <div class="flex space-x-3">
                    <div class="flex items-center mt-1 py-2">
                        <input data-filter="rented" value="3" id="type_3" name="type" type="radio" class="focus:ring-gray-600 text-gray-600 h-4 w-4 border-gray-300">
                        <label for="type_3" class="ml-2 block text-sm font-medium text-gray-700">{{ __($types[3]) }}</label>
                    </div>
                    <div class="flex items-center mt-1 py-2">
                        <input data-filter="rented" value="4" id="type_4" name="type" type="radio" class="focus:ring-gray-600 text-gray-600 h-4 w-4 border-gray-300">
                        <label for="type_4" class="ml-2 block text-sm font-medium text-gray-700">{{ __($types[4]) }}</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <label class="text-xs uppercase">{{ __('Sort By') }}</label>
                <select data-filter="rented-sort" name="sort_by" class="text-sm mt-1 py-2 block border border-gray-200 rounded-lg">
                    <option value="oldest" selected>{{ __('Oldest') }}</option>
                    <option value="latest">{{ __('Latest') }}</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button data-filter="rented" type="button" class="bg-primary text-white rounded px-3 py-2 text-sm filter">{{ __('Filter') }}</button>
                <button data-filter="rented" type="button" class="bg-secondary text-white rounded px-3 py-2 text-sm clear">{{ __('Clear') }}</button>
            </div>
        </div>
        <section id="rented" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5">
            @foreach($orders as $order)
            <div data-country="{{ $order->number->country }}" data-type="{{ $order->number->type }}" class="border-2 flex flex-col justify-between border-primary rounded-lg">
                <div class="flex justify-between p-5">
                    <div class="space-y-1">
                        <h3 class="text-primary font-semibold">+{{ $order->number->number }}</h3>
                        <div class="flex items-center space-x-1">
                            <span class="{{ $bg[$order->number->type] }} {{ $text[$order->number->type] }} px-2 py-1 text-xs font-bold rounded-full">{{ __($types[$order->number->type]) }}</span>
                            <span class="bg-gray-600 text-white px-2 py-1 text-xs rounded-full">{{ __('Expiring in') }} {{ $this->diffForHumans($order->expiry) }}</span>
                        </div>
                    </div>
                    <div>
                        <img src="https://flagcdn.com/h40/{{ strtolower($order->number->country) }}.png" alt="img">
                    </div>
                </div>
                @if(trim($order->number->meta))
                <h5 class="p-5 pt-0 text-sm text-gray-400">{{ $order->number->meta }}</h5>
                @endif
                <a class="w-full block text-center bg-primary text-white py-2 text-sm rounded-b" href="{{ route('number', $order->number->number) }}">
                    {{ __('View Messages') }}
                </a>
            </div>
            @endforeach
        </section>
    </div>
    @endif
    <div class="space-y-6" id="features">
        <h1 class="text-2xl font-bold text-primary">{{ __('Features') }}</h1>
        <div class="border-b border-dashed border-primary w-24"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5">
            @foreach($features as $feature)
            <div class="space-y-3">
                <div class="icon">
                    <i class="text-5xl text-secondary {{ $feature->icon }}"></i>
                </div>
                <h3 class="text-lg text-primary font-bold">{{ $feature->title }}</h3>
                <p class="pt-2">
                    {!! $feature->description !!}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-primary">{{ $sections[1]->title }}</h1>
        <div class="border-b border-dashed border-primary w-24"></div>
        <p>
            {!! $sections[1]->content !!}
        </p>
    </div>
    @for($i = 2; $i < count($sections); $i++)
    <div class="space-y-6 custom">
        <h1 class="text-2xl font-bold text-primary">{{ $sections[$i]->title }}</h1>
        <div class="border-b border-dashed border-primary w-24"></div>
        <p>
            {!! $sections[$i]->content !!}
        </p>
    </div>
    @endfor
</main>