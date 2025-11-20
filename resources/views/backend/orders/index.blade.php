<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="relative rounded-lg overflow-auto">
                <div class="shadow-sm overflow-hidden">
                    <table class="border-collapse table-auto w-full text-sm">
                        <thead class="bg-slate-600 pt-10 text-white rounded-t-lg">
                            <tr>
                                <th class="border-b font-medium p-4 pl-8 pt-5 pb-3 text-left">{{ __('User') }}</th>
                                <th class="border-b font-medium p-4 pt-5 pb-3 text-left">{{ __('Number') }}</th>
                                <th class="border-b font-medium p-4 pt-5 pb-3 text-left">{{ __('Plan') }}</th>
                                <th class="border-b font-medium p-4 pr-8 pt-5 pb-3 text-left">{{ __('Expiry') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white rounded-b-lg">
                            @foreach($orders as $order)
                            <tr>
                                <td class="border-b border-slate-100 p-4 pl-8 flex space-x-3">
                                    <img class="rounded-full" src="https://www.gravatar.com/avatar/{{ md5($order->user->email) }}?s=50" alt="gravatar">
                                    <div class="flex flex-col space-y-2">
                                        <span>{{ $order->user->name }}</span>
                                        <span class="text-xs">{{ $order->user->email }}</span>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 p-4">
                                    <span>+{{ $order->number->number }}</span>
                                </td>
                                <td class="border-b border-slate-100 p-4">
                                    {{ $order->plan->name }} - {{ config('app.settings.currency.symbol') }}{{ $order->plan->price }}
                                </td>
                                <td class="border-b border-slate-100 p-4 pr-8">
                                    {{ $order->expiry }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>