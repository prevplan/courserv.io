<div>
    <div>
        @unless($solved)
            <div>
                @error('validation') <div class="flex justify-center mt-1 text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="flex justify-center">
                <div class="flex justify-center frc-captcha" data-sitekey="{{ config('services.fcaptcha.sitekey') }}" data-callback="captchaSolved" data-lang="{{ LaravelLocalization::getCurrentLocale() }}"></div>
            </div>
        @endunless
    </div>

    <script>
        function captchaSolved(solution) {
            Livewire.emitTo('fcaptcha', 'captchaSolved', solution);
        }
    </script>

    {{-- remove captcha, if no friendly captcha key is present--}}
    @if(!config('services.fcaptcha.sitekey') || !config('services.fcaptcha.secret'))
        <div x-init="$wire.emit('removeCaptcha')"></div>
    @endif
</div>
