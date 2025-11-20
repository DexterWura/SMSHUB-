<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(config('app.settings.favicon')) }}" type="image/png">
    @yield('header')
    {!! config('app.settings.global.header') !!}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <script src="{{ asset('vendor/Shortcode/Shortcode.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @livewireStyles
    {!! config('app.settings.global.css') !!}
    @include('frontend.common.header')
</head>
<body>
    <div class="default-theme">
        <div class="container mx-auto px-5 md:px-0">
            <div class="flex justify-end text-white">
                @if(Auth::check())
                <a href="{{ route('wallet') }}" class="flex flex-col bg-primary rounded-b-lg px-5 py-2">
                    <span class="text-xs font-medium">{{ __('Wallet') }}</span>
                    <span class="text-xl">{{ config('app.settings.currency.symbol') }}{{ auth()->user()->wallet }}</span>
                </a>
                @else
                <a class="text-xs md:text-base flex gap-3 px-3 md:px-5 py-2 md:py-3 bg-primary rounded-bl-lg" href="{{ route('register') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>{{ __('Register') }}</span>
                </a>
                <a class="text-xs md:text-base flex gap-3 px-3 md:px-5 py-2 md:py-3 bg-secondary rounded-br-lg" href="{{ route('login') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 md:h-5 w-4 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('Login') }}</span>
                </a>
                @endif
            </div>
            <div class="flex gap-10">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img class="max-w-logo" src="{{ asset(config('app.settings.logo')) }}" alt="logo">
                    </a>
                </div>
                <div class="flex-1">
                    @livewire('frontend.nav')
                </div>
            </div>
            @if(Session::has('success'))
            <div class="bg-green-50 w-full flex justify-between items-center rounded-lg p-4">
                <div class="flex justify-start items-center space-x-5">
                    <div class="text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm text-green-800 font-medium">{{ __('Success!') }} {{ Session::get('success') }}</p>
                </div>
            </div>
            @endif
            @if(Session::has('error'))
            <div class="bg-red-50 w-full flex justify-between items-center rounded-lg p-4">
                <div class="flex justify-start items-center space-x-5">
                    <div class="text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm text-red-800 font-medium">{{ __('Error!') }} {{ Session::get('error') }}</p>
                </div>
            </div>
            @endif
            @yield('content')
        </div>
    </div>
    @livewireScripts
    {!! config('app.settings.global.js') !!}
    {!! config('app.settings.global.footer') !!}
    @yield('footer')
</body>
</html>