<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Carbon\Carbon::parse(Illuminate\Support\Facades\Storage::get('cron_log')) < Carbon\Carbon::now()->subMinutes(35))
            <div class="bg-red-50 border border-red-400 mb-3 w-full flex justify-between items-center rounded-lg p-4">
                <div class="flex justify-start items-center space-x-5">
                    <div class="text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm text-red-800 font-medium">
                        {{ __('Your CRON has not ran since ') . Carbon\Carbon::parse(Illuminate\Support\Facades\Storage::get('cron_log'))->diffForHumans() }}
                        {{ __('- CRON should be configured to run every 30 minutes for tSMS to work properly.') }}
                        <a class="border-b border-red-900" href="{{ route('setup') }}">{{ __('Click here for Setup Instructions') }}</a>
                    </p>
                </div>
            </div>
            @endif
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-10">
                    <strong class="text-2xl">
                        {{ __('Hi') }} {{ explode(' ', Auth::user()->name)[0] }}!
                    </strong>
                    <div class="mt-2 text-gray-500">
                        {{ __('Welcome to tSMS Dashboard') }}
                    </div>
                </div>
                <div class="bg-gray-50 text-gray-800 grid grid-cols-1 md:grid-cols-3 p-10 space-x-10">
                    <div>
                        <div class="flex items-center">
                            <div class="text-lg leading-7 font-semibold"><a href="{{ route('setup') }}">{{ __('Setup') }}</a></div>
                        </div>
                        <div>
                            <div class="mt-2 text-sm">
                                {{ __('You can view the setup instructions here that will show you various other part of tSMS that requires some manual steps of installations. ') }}
                            </div>
                            <a href="{{ route('setup') }}">
                                <div class="mt-3 flex items-center text-sm font-semibold text-sky-700">
                                    <div>{{ __('View Instructions') }}</div>
                                    <div class="ml-1 text-sky-700">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center">
                            <div class="text-lg leading-7 font-semibold"><a href="https://support.thehp.in/articles/100015612" target="_blank">{{ __('Articles') }}</a></div>
                        </div>
                        <div>
                            <div class="mt-2 text-sm">
                                {{ __('A bunch of helpful articles related to tSMS can be found in the below portal. This will help on various features of tSMS.') }}
                            </div>
                            <a href="https://support.thehp.in/articles/100015612" target="_blank">
                                <div class="mt-3 flex items-center text-sm font-semibold text-sky-700">
                                    <div>{{ __('Explore the Articles') }}</div>
                                    <div class="ml-1 text-sky-700">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center">
                            <div class="text-lg leading-7 font-semibold"><a href="https://support.thehp.in/submit/#100015612" target="_blank">{{ __('Support') }}</a></div>
                        </div>
                        <div class="">
                            <div class="mt-2 text-sm">
                                {{ __('In case if you\'re not able to find the right solution in the Articles section, we\'re always here to help you out at our Support Portal. ') }}
                            </div>
                            <a href="https://support.thehp.in/submit/#100015612" target="_blank">
                                <div class="mt-3 flex items-center text-sm font-semibold text-sky-700">
                                    <div>{{ __('Create a Support Ticket') }}</div>
                                    <div class="ml-1 text-sky-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-6 p-10">
                    <div class="flex space-x-5 items-center">
                        <div class="bg-lime-200 text-lime-600 w-16 h-16 rounded-full flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1 space-y-1">
                            <h3 class="text-xl font-bold">{{ $stats->total_numbers }}</h3>
                            <h5 class="text-sm">{{ __('Total Numbers') }}</h5>
                        </div>
                    </div>
                    <div class="flex space-x-5 items-center">
                        <div class="bg-cyan-200 text-cyan-600 w-16 h-16 rounded-full flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1 space-y-1">
                            <h3 class="text-xl font-bold">{{ $stats->total_messages }}</h3>
                            <h5 class="text-sm">{{ __('Total Messages Received') }}</h5>
                        </div>
                    </div>
                    <div class="flex space-x-5 items-center">
                        <div class="bg-violet-200 text-violet-600 w-16 h-16 rounded-full flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                        <div class="flex-1 space-y-1">
                            <h3 class="text-xl font-bold">{{ $stats->total_users }}</h3>
                            <h5 class="text-sm">{{ __('Total Registered Users') }}</h5>
                        </div>
                    </div>
                    <div class="flex space-x-5 items-center">
                        <div class="bg-rose-200 text-rose-600 w-16 h-16 rounded-full flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1 space-y-1">
                            <h3 class="text-xl font-bold">{{ $stats->total_orders }}</h3>
                            <h5 class="text-sm">{{ __('Total Paid Orders') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
