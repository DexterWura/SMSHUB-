<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Sections') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can manage the Custom Sections of your tSMS Homepage.') }}
    </x-slot>
    
    <x-slot name="form">
        @if($addSection || $updateSection)
            <div class="col-span-6 flex justify-between">
                <x-jet-secondary-button class="mr-2" wire:click="clearAddUpdate">
                    <i class="fas fa-caret-left"></i> <span class="ml-2">{{ __('Back') }}</span>
                </x-jet-secondary-button>
                @if(isset($section['lang']))
                <div class="bg-gray-200 rounded-md px-4 py-2 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                    </svg>
                    <span>{{ __('Add translations for') }} {{ $section['lang_text'] }} ({{ $section['lang'] }})</span>
                </div>
                @endif
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="title" value="{{ __('Name') }}" />
                <x-jet-input id="title" type="text" class="mt-1 block w-full" placeholder="Section Name" wire:model.defer="section.title"/>
                <x-jet-input-error for="section.title" class="mt-2" />
            </div>
            @if(!isset($section['lang']))
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md">
                    <span class="inline-flex items-center px-4 mt-1 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                       {{ config('app.url') }}/#
                    </span>
                    <x-jet-input id="slug" type="text" class="mt-1 block w-full rounded-none rounded-r-md" placeholder="Section Slug" wire:model.defer="section.slug"/>
                </div>
                <x-jet-input-error for="section.slug" class="mt-2" />
            </div>
            @endif
            <div class="col-span-6">
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <div class="mt-1"><div id="quill-content">{!! $section['content'] !!}</div></div>
                <textarea id="content" class="hidden" wire:model.defer="section.content"></textarea>
                <x-jet-input-error for="section.content" class="mt-2" />
            </div>
            @if(isset($section['id']) && !isset($section['lang']))
            <div class="col-span-6">
                <x-jet-label for="lang" value="{{ __('Add Translations') }} ({{ __('Optional') }})" />
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 mt-2">
                    @foreach(config('app.locales') as $index => $locale)
                    @if($locale != config('app.settings.language'))
                    <button wire:click="translate('{{ $locale }}')" type="button" class="{{ $this->isTranslated($locale) ? 'bg-green-600' : 'bg-gray-800' }} text-white text-sm px-3 py-2 rounded-md flex items-center space-x-2">
                        @if($this->isTranslated($locale))
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        @endif
                        <span>{{ config('app.locales_text')[$index] }}</span>
                    </button>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="col-span-6 -mt-4">
            @if(count($sections))
                @foreach($sections as $key => $section)
                <div class="bg-gray-800 text-white rounded-md px-5 py-4 mt-3 flex justify-between items-center">
                    <div class="flex">
                        <div>
                            {{ $section->title }}
                        </div>
                        <div class="px-3">-</div>
                        <div><small classs="text-xs"><a href="{{ env('APP_URL') }}/#{{ $section->slug }}" target="_blank">{{ env('APP_URL') }}/#{{ $section->slug }}</a></small></div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="cursor-pointer" wire:click="showUpdate({{ $section->id }})"><i class="fas fa-edit"></i></div>
                        @if($key >= 2)
                        <div class="cursor-pointer" wire:click="delete({{ $section->id }})"><i class="fas fa-trash-alt"></i></div>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="flex justify-center text-gray-600 text-sm pt-5 pb-3">{{ __('It is a Empty Space!') }}</div>
                <div class="flex justify-center text-5xl">🥺</div>
            @endif
            </div>
        @endif
        <style>
        .ql-toolbar {
            border-radius: 0.375rem 0.375rem 0 0;
        }
        .ql-container {
            border-radius: 0 0 0.375rem 0.375rem;
        }
        </style>
        <script>
        function loadQuill() {
            var toolbarOptions = [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'align': [] }],
                ['link', 'code-block'],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ];
            if(document.querySelector('.ql-toolbar') === null && document.getElementById('quill-content')) {
                var quill = new Quill('#quill-content', {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow',
                });
            }
        }
        function loadEventListeners() {
            setInterval(() => {
                if(document.querySelector('#quill-content .ql-editor')) {
                    document.querySelector('#content').value = document.querySelector('#quill-content .ql-editor').innerHTML;
                    document.querySelector('#content').dispatchEvent(new Event('input'));
                }
            }, 500);
        }
        if(document.getElementById('quill-content')) {
            loadQuill()
            loadEventListeners()
            window.addEventListener('componentUpdated', event => {
                loadQuill()
                loadEventListeners()
            })
        }
        window.addEventListener('clearQuill', event => {
            if(document.querySelector('#quill-content .ql-editor')) {
                document.querySelector('#quill-content .ql-editor').innerHTML = ''
            }
        })
        </script>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>
        @if($addSection || $updateSection)
            @if($addSection)
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:click="add">
                    {{ __('Add') }}
                </button>
            @else
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:click="update">
                    {{ __('Update') }}
                </button>
            @endif
        @else
            <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition ease-in-out duration-150" wire:click="$toggle('addSection')">
                {{ __('Add Section') }}
            </button>
        @endif
    </x-slot>
</x-jet-form-section>