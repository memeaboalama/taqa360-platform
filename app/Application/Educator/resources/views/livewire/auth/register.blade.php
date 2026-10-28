<form wire:submit.prevent="register" class="space-y-4" x-data="{ showPasswords: false }" novalidate>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="first_name" class="form-label">{{ __('educator::auth.first_name') }}</label>
            <input wire:model.defer="first_name" id="first_name" type="text"
                class="form-input @error('first_name') border-red-500 @enderror"
                placeholder="{{ __('educator::auth.first_name') }}">
            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="last_name" class="form-label">{{ __('educator::auth.last_name') }}</label>
            <input wire:model.defer="last_name" id="last_name" type="text"
                class="form-input @error('last_name') border-red-500 @enderror"
                placeholder="{{ __('educator::auth.last_name') }}">
            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label for="email" class="form-label">{{ __('educator::auth.email') }}</label>
        <input wire:model.defer="email" id="email" type="email"
            class="form-input @error('email') border-red-500 @enderror" placeholder="{{ __('educator::auth.email') }}">
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="password" class="form-label">{{ __('educator::auth.password') }}</label>
        <div class="relative">
            <input wire:model.defer="password" id="password" :type="showPasswords ? 'text' : 'password'"
                class="form-input pr-10 @error('password') border-red-500 @enderror"
                placeholder="{{ __('educator::auth.password') }}">
            <button type="button" @click="showPasswords = !showPasswords"
                class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center text-gray-400 hover:text-gray-600">
                <svg x-show="!showPasswords" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg x-show="showPasswords" x-cloak xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                    </path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
            </button>
        </div>
        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="password_confirmation" class="form-label">{{ __('educator::auth.password_confirmation') }}</label>
        <div class="relative">
            <input wire:model.defer="password_confirmation" id="password_confirmation"
                :type="showPasswords ? 'text' : 'password'" class="form-input pr-10"
                placeholder="{{ __('educator::auth.password_confirmation') }}">
            <button type="button" @click="showPasswords = !showPasswords"
                class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center text-gray-400 hover:text-gray-600">
                <svg x-show="!showPasswords" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg x-show="showPasswords" x-cloak xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                    </path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
            </button>
        </div>
        @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-center">
        <input wire:model.defer="agree_terms" id="agree_terms" type="checkbox"
            class="h-4 w-4 rounded border-gray-300 focus:ring-2" style="color: var(--primary-color);">
        <label for="agree_terms"
            class="block text-sm text-gray-700 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">
            {{ __('educator::auth.agree_to') }}
            <a href="/terms">{{ __('educator::auth.terms_and_conditions') }}</a>
        </label>
    </div>
    @error('agree_terms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

    <button type="submit"
        class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed"
        wire:loading.attr="disabled" wire:target="register">
        <svg wire:loading x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        <span>{{ __('educator::auth.register') }}</span>
    </button>
</form>
