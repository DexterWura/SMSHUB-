<main class="my-10">
    <div class="number space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start">
            <div class="space-y-6">
                <h1 class="text-2xl font-bold text-primary">{{ __('Messages for') }} +{{ $number->number }}</h1>
                <div class="border-b border-dashed border-primary w-24"></div>
            </div>
            @if(isset($auto_renew))
            <div class="flex flex-col gap-3 py-5 md:py-0 md:items-end">
                <label for="auto_renew" class="flex items-center cursor-pointer">
                    <div class="block font-medium text-sm text-gray-700 mr-4">
                        {{ __('Enable Auto Renew') }}
                    </div>
                    <div class="relative">
                        <input id="auto_renew" type="checkbox" class="hidden" wire:model="auto_renew" />
                        <div class="toggle-path bg-gray-200 w-9 h-5 rounded-full shadow-inner"></div>
                        <div class="toggle-circle absolute w-3.5 h-3.5 bg-white rounded-full shadow inset-y-0 left-0"></div>
                    </div>
                </label>
                <small class="md:text-right text-xs text-gray-500 md:max-w-xs">{{ __('Once enabled, your order / number will be automatically renewed if you have a sufficient balance in your wallet.') }}</small>
            </div>
            @endif
        </div>
        @if(config('app.settings.ads.three'))
        <div class="max-w-full ads-three">{!! config('app.settings.ads.three') !!}</div>
        @endif
        @if($captchaError)
        {{ $captchaError }}
        @endif
        @if($timestamp < time())
            @if(config('app.settings.captcha') == 'hcaptcha' || config('app.settings.captcha') == 'recaptcha2')
            <x-captcha field="captcha" />
            @endif
        @else
            <div class="flex flex-col space-y-3">
                @if(count($messages) == 0)
                <p class="text-gray-500">{{ __('No Messages to Show') }}</p>
                @endif
                @foreach($messages as $i => $message)
                @if($i != 0 && $i % 3 == 0 && config('app.settings.ads.four'))
                <div class="max-w-full ads-four">{!! config('app.settings.ads.four') !!}</div>
                @endif
                <div class="messages flex w-100">
                    <div class="bg-gray-100 p-5 rounded-l-lg">
                        <label class="text-xs">{{ __('Sender') }}</label>
                        @if($is_admin)
                        <div>+{{ $message->from }}</div>
                        @else
                        <div>+{{ substr($message->from, 0, -5) . 'XXXXX' }}</div>
                        @endif
                    </div>
                    <div class="bg-gray-200 p-5 flex-1">
                        <label class="text-xs">{{ __('Message') }}</label>
                        <div class="content">{{ $message->msg }}</div>
                        <button class="hidden mt-2 px-2 py-1 text-xs bg-gray-600 text-white rounded copy-code disabled:bg-gray-400" data-original="{{ __('Copy Code') }}" data-alt="{{ __('Copied') }}">{{ __('Copy Code') }}</button>
                    </div>
                    <div class="bg-gray-100 p-5 rounded-r-lg">
                        <label class="text-xs">{{ __('Time') }}</label>
                        <div>{{ $message->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
                {{ $messages->links() }}
            </div>
        @endif
        @if(config('app.settings.ads.five'))
        <div class="max-w-full ads-five">{!! config('app.settings.ads.five') !!}</div>
        @endif
    </div>
    @if(config('app.settings.captcha') == 'recaptcha3')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('app.settings.recaptcha3.site_key') }}"></script>
    <script>
        grecaptcha.ready(function() {
        console.log('working')
            grecaptcha.execute('{{ config('app.settings.recaptcha3.site_key') }}', { action: 'submit' }).then(function(token) {
                Livewire.emit('checkReCaptcha3', token);
            });
        });
    </script>
    @elseif(config('app.settings.captcha') == 'hcaptcha' || config('app.settings.captcha') == 'recaptcha2')
        @if($timestamp < time())
        <script>
            const captchaInterval = setInterval(() => {
                let captcha_type = "{{ config('app.settings.captcha') }}";
                if(captcha_type == 'hcaptcha' && document.querySelector('[name="h-captcha-response"]') && document.querySelector('[name="h-captcha-response"]').value) {
                    Livewire.emit('setCaptcha', document.querySelector('[name="h-captcha-response"]').value);
                    clearInterval(captchaInterval);
                } else if (captcha_type == 'recaptcha2' && document.querySelector('[name="g-recaptcha-response"]') && document.querySelector('[name="g-recaptcha-response"]').value) {
                    Livewire.emit('setCaptcha', document.querySelector('[name="g-recaptcha-response"]').value);
                    clearInterval(captchaInterval);
                }
            }, 100);
        </script>
        @endif
    @endif
    <script>
        document.querySelectorAll('.number .messages').forEach(el => {
            const msg = el.querySelector('.content').innerText
            const btn = el.querySelector('.copy-code')
            const regex = /[0-9]{4,10}/g
            const result = regex.exec(msg)
            if (result) {
                btn.classList.remove('hidden')
                btn.setAttribute('onclick', `this.disabled = true; this.innerText = this.dataset.alt; var e = this; setTimeout(function(){ e.innerText = e.dataset.original; e.disabled = false }, 2000); copyToClipboard('${result[0]}')`)
            }
        })
        const copyToClipboard = (text) => {
            let el = document.createElement('input');
            el.type = 'text';
            el.value = text;
            document.body.appendChild(el);
            var isiOSDevice = navigator.userAgent.match(/ipad|iphone/i);
            if (isiOSDevice) {
                var editable = el.contentEditable;
                var readOnly = el.readOnly;
                el.contentEditable = true;
                el.readOnly = false;
                var range = document.createRange();
                range.selectNodeContents(el);
                var selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                el.setSelectionRange(0, 999999);
                el.contentEditable = editable;
                el.readOnly = readOnly;
            } else {
                el.select();
            }
            document.execCommand('copy');
            el.remove();
        }
    </script>
</main>