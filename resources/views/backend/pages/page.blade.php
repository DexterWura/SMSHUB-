<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Pages') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can manage the Custom Pages of your tSMS here.') }}
    </x-slot>
    
    <x-slot name="form">
        @if($addPage || $updatePage)
            <div class="col-span-6 flex justify-between">
                <x-jet-secondary-button class="mr-2" wire:click="clearAddUpdate">
                    <i class="fas fa-caret-left"></i> <span class="ml-2">{{ __('Back') }}</span>
                </x-jet-secondary-button>
                @if(isset($page['lang']))
                <div class="bg-gray-200 rounded-md px-4 py-2 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                    </svg>
                    <span>{{ __('Add translations for') }} {{ $page['lang_text'] }} ({{ $page['lang'] }})</span>
                </div>
                @endif
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="title" value="{{ __('Name') }}" />
                <x-jet-input id="title" type="text" class="mt-1 block w-full" placeholder="Page Name" wire:model.defer="page.title"/>
                <x-jet-input-error for="page.title" class="mt-2" />
            </div>
            @if(($page['slug'] != 'order') && !isset($page['lang']))
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md">
                    <span class="inline-flex items-center px-4 mt-1 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                       {{ config('app.url') }}/
                    </span>
                    <x-jet-input id="slug" type="text" class="mt-1 block w-full rounded-none rounded-r-md" placeholder="Page Slug" wire:model.defer="page.slug"/>
                </div>
                <x-jet-input-error for="page.slug" class="mt-2" />
            </div>
            @endif
            <div class="col-span-6">
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <div class="mt-1"><div id="quill-content">{!! $page['content'] !!}</div></div>
                <textarea id="content" class="hidden" wire:model.defer="page.content"></textarea>
                <x-jet-input-error for="page.content" class="mt-2" />
            </div>
            @if($page['slug'] != 'order' && $show_parent && !isset($page['lang']))
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="parent" value="{{ __('Parent') }} ({{ __('Optional') }})" />
                <div class="relative">
                    <select class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model.defer="page.parent_id">
                        <option value="0">None</option>
                        @foreach($pages as $p)
                        @if(($addPage && $p->parent_id === null) || ($updatePage && $p->id !== $page['id']))
                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <x-jet-input-error for="page.parent" class="mt-2" />
            </div>
            @endif
            <div class="col-span-6">
                <x-jet-label value="{{ __('Meta Tags') }} ({{ __('Optional') }})" />
                @foreach($page['meta'] as $key => $meta)
                <div class="flex mt-2">
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">{{ __('Name') }}</label>
                        <div class="relative">
                            <select class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full cursor-pointer" wire:model.defer="page.meta.{{ $key }}.name">
                                <option value='' disabled selected>Select</option>
                                <option value='description'>{{ __('description') }}</option>
                                <option value='robots'>{{ __('robots') }}</option>
                                <option value='canonical'>{{ __('canonical') }}</option>
                                <option value='og:type'>{{ __('og:type') }}</option>
                                <option value='og:title'>{{ __('og:title') }}</option>
                                <option value='og:description'>{{ __('og:description') }}</option>
                                <option value='og:image'>{{ __('og:image') }}</option>
                                <option value='og:url'>{{ __('og:url') }}</option>
                                <option value='og:site_name'>{{ __('og:site_name') }}</option>
                                <option value='twitter:title'>{{ __('twitter:title') }}</option>
                                <option value='twitter:description'>{{ __('twitter:description') }}</option>
                                <option value='twitter:image'>{{ __('twitter:image') }}</option>
                                <option value='twitter:site'>{{ __('twitter:site') }}</option>
                                <option value='twitter:creator'>{{ __('twitter:creator') }}</option>
                            </select>
                        </div>
                        <x-jet-input-error for="page.meta.{{ $key }}.name" class="mt-2" />
                    </div>
                    <div class="flex-1 ml-3">
                        <label class="block font-medium text-gray-700 text-xs">{{ __('Content') }}</label>
                        <div class="flex">
                            <x-jet-input type="text" class="mt-1 block w-full" wire:model.defer="page.meta.{{ $key }}.content"/>
                            <button type="button" wire:click="deleteMeta({{ $key }})" class="form-input rounded-md ml-3 mt-1 px-3 bg-red-700 text-white border-0"><i class="fas fa-trash"></i></button>  
                        </div>
                        <x-jet-input-error for="page.meta.{{ $key }}.content" class="mt-2" />
                    </div>
                </div>
                @endforeach
                <button type="button" wire:click="addMeta" class="mt-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">{{ __('Add') }}</button>
            </div>
            <div class="col-span-6">
                <x-jet-label for="header" value="{{ __('Custom Header') }} ({{ __('Optional') }})" />
                <textarea id="header" class="form-input border-gray-300 rounded-md shadow-sm mt-1 block w-full resize-y border" placeholder="Enter the Custome Header" wire:model.defer="page.header"></textarea>
                <x-jet-input-error for="page.header" class="mt-2" />
            </div>
            @if(isset($page['id']) && !isset($page['lang']))
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
            @if(count($pages))
                @foreach($pages as $page)
                <div class="bg-gray-800 text-white rounded-md px-5 py-4 mt-3 flex justify-between items-center">
                    <div class="flex">
                        <div>
                            {{ $page->title }}
                        </div>
                        <div class="px-3">-</div>
                        <div><small classs="text-xs"><a href="{{ env('APP_URL') }}{{ ($page->parent_id) ? '/' . $page->parent_slug : '' }}/{{ $page->slug }}" target="_blank">{{ env('APP_URL') }}{{ ($page->parent_id) ? '/' . $page->parent_slug : '' }}/{{ $page->slug }}</a></small></div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="cursor-pointer" wire:click="showUpdate({{ $page->id }})"><i class="fas fa-edit"></i></div>
                        @if($page['slug'] != 'order')
                        <div class="cursor-pointer" wire:click="delete({{ $page->id }})"><i class="fas fa-trash-alt"></i></div>
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
        @if($addPage || $updatePage)
            @if($addPage)
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:click="add">
                    {{ __('Add') }}
                </button>
            @else
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:click="update">
                    {{ __('Update') }}
                </button>
            @endif
        @else
            <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition ease-in-out duration-150" wire:click="$toggle('addPage')">
                {{ __('Add Page') }}
            </button>
        @endif
    </x-slot>
</x-jet-form-section>