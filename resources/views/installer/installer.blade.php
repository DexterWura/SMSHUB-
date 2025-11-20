<div class="grid grid-cols-6 gap-6">
    <div wire:loading>
        <div class="flex items-center w-full h-full fixed top-0 left-0 bg-white opacity-75 z-50">
            <div class="text-3xl mx-auto">
                <i class="fas fa-sync-alt fa-spin"></i>
            </div>
        </div>
    </div>
    @if($success)
    <div class="col-span-6">
        <div class="w-full py-3 px-4 overflow-hidden rounded-md flex items-center border bg-green-50 border-green-500">
            <div class="text-green-500 w-10"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <div class="text-lg text-gray-600 font-semibold">{{ __('Success') }}</div>
                <div class="text-sm">{{ $success }}</div>
            </div>
        </div>
    </div>
    @endif
    @if($error)
    <div class="col-span-6">
        <div class="w-full py-3 px-4 overflow-hidden rounded-md flex items-center border bg-red-50 border-red-500">
            <div class="text-red-500 w-10"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path> 
                </svg> 
            </div>
            <div class="ml-4 flex-1">
                <div class="text-lg text-gray-600 font-semibold">{{ __('Error') }}</div>
                <div class="text-sm">{{ $error }}</div>
            </div>
        </div>
    </div>
    @endif
    @if($current === 0)
        <h3 class="col-span-6 text-xl pt-2">{{ __('Database Details') }}</h3>
        <div class="col-span-6">
            <x-jet-label for="hostname" value="{{ __('Hostname') }}" />
            <x-jet-input id="hostname" type="text" class="mt-1 block w-full" wire:model.defer="state.db.host"/>
            <x-jet-input-error for="state.db.host" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="port" value="{{ __('Port') }}" />
            <x-jet-input id="port" type="text" class="mt-1 block w-full" wire:model.defer="state.db.port"/>
            <x-jet-input-error for="state.db.port" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="connection" value="{{ __('Connection') }}" />
            <x-jet-input id="connection" type="text" class="mt-1 block w-full" wire:model.defer="state.db.connection"/>
            <x-jet-input-error for="state.db.connection" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="db_name" value="{{ __('Database') }}" />
            <x-jet-input id="db_name" type="text" class="mt-1 block w-full" wire:model.defer="state.db.database"/>
            <x-jet-input-error for="state.db.database" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="db_username" value="{{ __('Username') }}" />
            <x-jet-input id="db_username" type="text" class="mt-1 block w-full" wire:model.defer="state.db.username"/>
            <x-jet-input-error for="state.db.username" class="mt-2" />
        </div>
        <div x-data="{ show: false }" class="col-span-6">
            <x-jet-label for="db_password" value="{{ __('Password') }}" />
            <div class="relative">
                <x-jet-input id="db_password" x-bind:type="show ? 'text' : 'password'" class="mt-1 block w-full" wire:model.defer="state.db.password"  autocomplete="new-password"/>
                <div x-on:click="show = !show" x-text="show ? 'HIDE' : 'SHOW'" class="cursor-pointer absolute inset-y-0 right-0 flex items-center px-5 text-xs"></div>
            </div>
            <x-jet-input-error for="state.db.password" class="mt-2" />
        </div>
    @elseif($current === 1)
        <div class="col-span-6">
            <label class="block font-medium text-sm text-gray-700" for="app_name">{{ __('App Name') }}</label>
            <x-jet-input id="app_name" type="text" class="mt-1 block w-full" placeholder="{{ __('Enter the App Name') }}" wire:model.defer="state.app_name"/>
            <x-jet-input-error for="state.app_name" class="mt-2" />
        </div>
    @elseif($current === 2)
        <h3 class="col-span-6 text-xl pt-2">{{ __('Admin Account') }}</h3>
        <div class="col-span-6">
            <label class="block font-medium text-sm text-gray-700" for="name">{{ __('Name') }}</label>
            <x-jet-input id="name" type="text" class="mt-1 block w-full" placeholder="{{ __('Enter the Name') }}" wire:model.defer="state.admin.name"/>
            <x-jet-input-error for="state.admin.name" class="mt-2" />
        </div>
        <div class="col-span-6">
            <label class="block font-medium text-sm text-gray-700" for="email_id">{{ __('Email ID') }}</label>
            <x-jet-input id="email_id" type="email" class="mt-1 block w-full" placeholder="{{ __('Enter the Email ID') }}" wire:model.defer="state.admin.email"/>
            <x-jet-input-error for="state.admin.email" class="mt-2" />
        </div>
        <div x-data="{ show_admin_password: false }" class="col-span-6">
            <x-jet-label for="admin_password" value="{{ __('Password') }}" />
            <div class="relative">
                <x-jet-input id="admin_password" x-bind:type="show_admin_password ? 'text' : 'password'" class="mt-1 block w-full" placeholder="{{ __('Enter the Password') }}" wire:model.defer="state.admin.password" autocomplete="new-password"/>
                <div x-on:click="show_admin_password = !show_admin_password" x-text="show_admin_password ? 'HIDE' : 'SHOW'" class="cursor-pointer absolute inset-y-0 right-0 flex items-center px-5 text-xs"></div>
            </div>
            <x-jet-input-error for="state.admin.password" class="mt-2" />
        </div>
    @endif
    @if($current === 3)
    <div class="col-span-6">
        <a class="w-full block text-center bg-primary p-3 rounded-md text-white border-0 text-sm" href="{{ route('login') }}"><span class="mr-3 font-bold">{{ __('Visit tSMS Admin Panel') }}</span><i class="fas fa-arrow-right"></i></a>
    </div>
    @else
    <div class="col-span-6">
        <button wire:click="save" class="w-full bg-primary p-3 rounded-md text-white border-0 text-sm"><span class="mr-3 font-bold">{{ __('Save & Next') }}</span><i class="fas fa-arrow-right"></i></button>
    </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(sessionStorage.getItem('migrations')) {
                setTimeout(function(){ Livewire.emit('runMigrations') }, 10);
                sessionStorage.removeItem('migrations');
            }
        }, false);
        window.addEventListener('run-migrations', () => {
            sessionStorage.setItem('migrations', true);
            location.reload();
        })
    </script>
</div>