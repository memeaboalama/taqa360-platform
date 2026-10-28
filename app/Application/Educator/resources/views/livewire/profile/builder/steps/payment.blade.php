<div>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.payment')</h2>
                <p class="text-gray-600">@lang('educator::builder.payment.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto bg-white border border-gray-200 rounded-lg shadow-sm" x-data>
        <div class="p-6 text-center border-b border-gray-200">
            <div class="flex items-center justify-center gap-2 mb-2">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
                </svg>
                <h4 class="text-lg font-semibold">@lang('educator::builder.payment.bank_account_details')</h4>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="space-y-2">
                <label for="accountHolderName"
                    class="block text-sm font-medium text-gray-700">@lang('educator::builder.payment.account_holder_name')</label>
                <input wire:model.defer="bankInfo.name" id="accountHolderName" type="text"
                    placeholder="@lang('educator::builder.payment.cardholder_name_placeholder')"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                @error('bankInfo.name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="iban"
                    class="block text-sm font-medium text-gray-700">@lang('educator::builder.payment.iban_number')</label>
                <input wire:model.defer="bankInfo.iban" id="iban" type="text"
                    placeholder="@lang('educator::builder.payment.iban_number_placeholder')"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                @error('bankInfo.iban') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="bankName"
                    class="block text-sm font-medium text-gray-700">@lang('educator::builder.payment.bank_name')</label>
                <input wire:model.defer="bankInfo.bank_name" id="bankName" type="text"
                    placeholder="@lang('educator::builder.payment.bank_name_placeholder')"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                @error('bankInfo.bank_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="bankCountry"
                        class="block text-sm font-medium text-gray-700">@lang('educator::builder.payment.bank_country')</label>
                    <input wire:model.defer="bankInfo.bank_country" id="bankCountry" type="text"
                        placeholder="@lang('educator::builder.payment.bank_country_placeholder')"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    @error('bankInfo.bank_country') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="bankCode"
                        class="block text-sm font-medium text-gray-700">@lang('educator::builder.payment.bank_code')</label>
                    <input wire:model.defer="bankInfo.bank_code" id="bankCode" type="text"
                        placeholder="@lang('educator::builder.payment.bank_code_placeholder')"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                    @error('bankInfo.bank_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-start gap-2 p-3 bg-gray-50 rounded-lg">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.62-3.75M12 5.036V12m0 0h3.75M12 12L8.25 5.036" />
                </svg>
                <div class="text-sm">
                    <p class="font-medium text-gray-900">@lang('educator::builder.payment.security_info_title')</p>
                    <p class="text-gray-600">@lang('educator::builder.payment.security_info_body')</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between pt-6 border-t mt-8">
        <button wire:click="$emitUp('goToPreviousStep')" type="button"
            class="px-8 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-semibold">@lang('educator::builder.previous')</button>
        <button wire:click="save" wire:loading.attr="disabled" wire:target="save" type="button"
            class="px-8 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-semibold inline-flex items-center justify-center gap-2">
            <svg wire:loading wire:target="save" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.finish_setup')</span>
        </button>
    </div>
</div>
