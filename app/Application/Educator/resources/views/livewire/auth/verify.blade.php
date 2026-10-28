<div x-data="{
        resendDisabled: true,
        timerText: '',
        timerInterval: null,
        startTimer(expiryTimestamp) {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timerInterval = setInterval(() => {
                const remaining = expiryTimestamp - Math.floor(Date.now() / 1000);
                if (remaining <= 0) {
                    clearInterval(this.timerInterval);
                    this.resendDisabled = false;
                    this.timerText = '';
                    return;
                }
                this.resendDisabled = true;
                this.timerText = `${String(Math.floor(remaining / 60)).padStart(2, '0')}:${String(remaining % 60).padStart(2, '0')}`;
            }, 1000);
        }
    }"
    x-init="startTimer({{ $cooldownExpiresAt }})"
    @cooldown-updated.window="startTimer($event.detail.expiresAt)">

    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold">{{ __('educator::auth.verify_title') }}</h2>
        <p class="text-gray-600 mt-2">
            {{ __('educator::auth.code_sent') }}
            <span class="font-medium dir-ltr d-inline-block">{{ $email }}</span>.
        </p>
    </div>

     {{-- @if(config('app.debug') && $debugOtp) --}}
        <div class="my-4 p-3 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-lg text-center">
            <p class="text-sm">@lang('educator::auth.debug_mode_otp_is')</p>
            <p class="text-2xl font-bold tracking-widest mt-1">{{ session()->get('debugOtp') }}</p>
        </div>
    {{-- @endif --}}
    <form wire:submit.prevent="verifyOtp" class="space-y-5">
        <div class="flex justify-center gap-2 md:gap-3" dir="ltr">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" maxlength="1" wire:model.defer="otp.{{$i}}" x-ref="otp-input-{{$i}}"
                    @input="if (event.target.value.match(/^[0-9]$/)) { if ({{$i}} < 5) { $refs['otp-input-{{$i+1}}'].focus() } } else { event.target.value = '' }"
                    @keydown.backspace="if (event.key === 'Backspace' && !event.target.value && {{$i}} > 0) { $refs['otp-input-{{$i-1}}'].focus() }"
                    class="form-input w-12 h-14 text-center text-2xl font-semibold @error('otp') border-red-500 @enderror" required>
            @endfor
        </div>
        @error('otp') <div class="text-center text-red-500 text-sm -mt-2 mb-2">{{ $message }}</div> @enderror

        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="verifyOtp">{{ __('educator::auth.verify') }}</span>
            <span wire:loading wire:target="verifyOtp">{{ __('educator::auth.verifying') }}</span>
        </button>
    </form>

    <div class="mt-6 text-center text-sm">
        <p class="text-gray-600">
            {{ __('educator::auth.did_not_receive_code') }}
            <button type="button" wire:click="resendCode" x-bind:disabled="resendDisabled" class="font-medium disabled:text-gray-400 disabled:cursor-not-allowed">
                {{ __('educator::auth.resend_code') }} <span x-show="resendDisabled" x-text="'(' + timerText + ')'"></span>
            </button>
        </p>
    </div>
</div>
