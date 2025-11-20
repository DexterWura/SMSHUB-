<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Setup Instructions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6 space-y-5">
                <h2 class="text-xl font-bold">{{ __('SMS Gateway Setup') }}</h2>
                <p>{{ __('You will need to configure your SMS Gateway to forward all the incoming messages to below URL.') }}</p>
                <div class="bg-gray-100 p-5 rounded-lg text-sm font-mono">
                    {{ route('receive', config('app.settings.auth_key')) }}
                </div>
                <p class="pt-5">{{ __('You can setup a GET or a POST call and below is the parameters that you will need to configure') }}</p>
                <div class="relative rounded-xl overflow-auto">
                    <div class="shadow-sm overflow-hidden my-8">
                        <table class="border-collapse table-auto w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Parameter') }}</th>
                                    <th class="border-b font-medium p-4 pt-0 pb-3 text-slate-400 text-left">{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-50">
                                @foreach([
                                    'to' => __('Phone number with country code to which message is sent'),
                                    'from' => __('Phone number with country code from which message is sent'),
                                    'msg' => __('Actual content of the message sent'),
                                    'uuid' => __('Unique ID of the message')
                                ] as $parameter => $detail)
                                <tr>
                                    <td class="border-b border-slate-100 p-4 pl-8 text-slate-500">{{ $parameter }}</td>
                                    <td class="border-b border-slate-100 p-4 text-slate-500">{{ $detail }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6 space-y-5">
                <h2 class="text-xl font-bold">{{ __('Cron Setup') }}</h2>
                <p>{{ __('Setup a CRON job that runs every 30 minutes. Below is the CRON command that you can use.') }}</p>
                <div class="bg-gray-100 p-5 rounded-lg text-sm font-mono">
                    wget -O /dev/null -o /dev/null {{ route('cron', config('app.settings.cron_password')) }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
