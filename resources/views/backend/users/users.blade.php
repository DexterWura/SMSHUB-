<div class="space-y-5">
    <div class="flex space-x-3 items-center">
        <input type="text" wire:model.debounce.500ms="search" name="search" id="search" autocomplete="autoarticle-search" class="flex-1 py-4 focus:ring-lime-500 focus:border-lime-500 block w-full shadow sm:text-sm border-0 rounded-lg" placeholder="Search by Name or Email">
        <select name="sort_by" id="sort_by" wire:model="orderby" class="py-4 focus:ring-lime-500 focus:border-lime-500 block shadow sm:text-sm border-0 rounded-lg">
            <option value="id" disabled selected>{{ __('Sort By')}}</option>
            <option value="name">{{ __('Name') }}</option>
            <option value="wallet">{{ __('Wallet') }}</option>
            <option value="email">{{ __('Email') }}</option>
            <option value="created_at">{{ __('Date') }}</option>
        </select>
        <div x-data="{ order: 'asc' }">
            <span class="text-xs">{{ __('ORDER') }}</span>
            <div class="flex justify-center cursor-pointer" wire:click="changeOrder">
                <svg x-show="order === 'asc'" x-on:click="order = 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                </svg>
                <svg x-show="order === 'desc'" x-on:click="order = 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
                </svg>
            </div>
        </div>
    </div>
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($users as $user)
        <div class="bg-white rounded-lg shadow">
            <div class="flex justify-between p-5">
                <div class="space-y-1">
                    <div class="flex space-x-3">
                        <h4 class="font-semibold">{{ $user->name }}</h4>
                        @if($user->role == 7)
                        <div class="bg-red-200 text-red-900 rounded-full px-2 py-1 text-xs">{{ __('Admin') }}</div>
                        @endif
                    </div>
                    <h5 class="text-sm text-gray-400">{{ $user->email }}</h5>
                </div>
                <div>
                    <img class="h-10 w-10 object-cover rounded-full" src="https://s.gravatar.com/avatar/{{ md5($user->email) }}" alt="img">
                </div>
            </div>
            <div class="p-5 flex justify-center items-center">
                <div class="flex-1 text-sm">
                    {{ config('app.settings.currency.symbol') }}{{ $user->wallet }} {{ __('Balance') }}
                </div>
                <div class="flex gap-2">
                    <button class="bg-gray-500 text-white px-3 py-2 rounded-lg text-xs flex items-center space-x-1" wire:click="showWalletDialog('{{ $user->id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>{{ __('Update Balance') }}</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    {{ $users->links() }}
    <div class="text-center text-gray-500">{{ $message }}</div>
    <x-jet-dialog-modal maxWidth="sm" wire:model="user">
        <x-slot name="title">
            {{ __('Update Balance in Wallet') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex">
                <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                    {{ config('app.settings.currency.symbol') }}
                </span>
                <input type="number" min="0" max="99999999" wire:model.defer="wallet" name="wallet" id="wallet" class="focus:ring-lime-500 focus:border-lime-500 block w-full sm:text-sm border border-gray-300 rounded-lg rounded-l-none" required>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-button wire:click="updateCredits">
                {{ __('Update') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
    <div wire:loading>
        <div class="fixed top-0 left-0 w-screen h-screen bg-white text-gray-900 bg-opacity-75 z-20 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-40 w-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
    </div>
</div>