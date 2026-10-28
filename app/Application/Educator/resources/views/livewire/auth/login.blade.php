<div>
    @if($redirecting)
        <div style="position: fixed; inset: 0; z-index: 99999; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <div class="mb-4">
                <span style="width: 80px; height: 80px; background: rgba(var(--primary-rgb), 0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="rgb(var(--primary-rgb))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </span>
            </div>
            <h3 class="font-bold mb-1" style="color: #000;">{{ __('educator::auth.welcome_back_msg', ['name' => $welcomeName]) }}</h3>
            <p class="text-gray-500 mb-4">{{ __('educator::auth.redirecting_msg') }}</p>
            <div class="w-8 h-8 border-4 border-gray-200 border-t-transparent rounded-full animate-spin" style="border-top-color: rgb(var(--primary-rgb));"></div>
        </div>
        <script>
            setTimeout(function () {
                window.location.href = @json($redirectUrl);
            }, 1500);
        </script>
    @else
    <form wire:submit.prevent="login" class="space-y-4" novalidate>
    <div>
        <label for="email" class="form-label">{{ __('educator::auth.email') }}</label>
        <input wire:model.defer="email" id="email" type="email" autocomplete="email"
            class="form-input @error('email') border-red-500 @enderror" placeholder="{{ __('educator::auth.email') }}">
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div x-data="{ show: false }">
        <label for="password" class="form-label">{{ __('educator::auth.password') }}</label>
        <div class="relative">
            <input wire:model.defer="password" id="password" :type="show ? 'text' : 'password'"
                class="form-input pr-10 @error('password') border-red-500 @enderror"
                placeholder="{{ __('educator::auth.password') }}">
            <button type="button" @click="show = !show"
                class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center text-gray-400 hover:text-gray-600">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
            </button>
        </div>
        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 focus:ring-2"
                style="color: var(--primary-color);">
            <label for="remember_me"
                class="text-sm text-gray-700 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('educator::auth.remember_me') }}</label>
        </div>
        <div class="text-sm">
            <a href="{{ route('educator:forgetPassword') }}" class="hover:underline">{{ __('educator::auth.forgot_your_password') }}</a>
        </div>
    </div>

    <button type="submit"
        class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed"
        wire:loading.attr="disabled" wire:target="login">
        <svg wire:loading x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        <span>{{ __('educator::auth.login') }}</span>
    </button>
    </form>
    @endif
</div>
