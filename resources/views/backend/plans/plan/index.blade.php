<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center space-x-3">
            <a href="{{ route('plans') }}">{{ __('Plans') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <div>{{ __('Map Numbers to') }} <span class="border-b">{{ $plan->name }}</span> {{ __('Plan') }}</div>
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('backend.plans.plan.map', ['plan_id' => $plan->id])
        </div>
    </div>
</x-app-layout>