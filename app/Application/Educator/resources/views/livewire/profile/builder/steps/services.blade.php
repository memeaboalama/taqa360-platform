<div>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.07a2.25 2.25 0 01-2.25 2.25h-13.5a2.25 2.25 0 01-2.25-2.25v-4.07m16.5-8.25a2.25 2.25 0 00-2.25-2.25h-13.5a2.25 2.25 0 00-2.25 2.25m16.5 0v4.07m-16.5-4.07v4.07m16.5 0v3.75m-16.5-3.75v3.75m16.5 0a2.25 2.25 0 01-2.25 2.25h-13.5a2.25 2.25 0 01-2.25-2.25m16.5 0m-16.5 0" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.services')</h2>
                <p class="text-gray-600">@lang('educator::builder.services.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($educatorServices as $service)
            <div wire:key="service-item-wrapper-{{ $service->id }}">
                <div class="bg-white border rounded-lg p-4 flex items-center justify-between gap-4 transition-all duration-300 {{ $editingId === $service->id ? 'border-primary shadow-lg ring-2 ring-primary/20' : 'border-gray-200' }}">
                    <div>
                        <h4 class="font-bold text-foreground">{{ $service->is_custom ? $service->custom_name : $service->approvedService?->name }}</h4>
                        <p class="text-sm text-gray-600 max-w-lg truncate">{{ $service->custom_description }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button wire:click="edit({{ $service->id }})" type="button" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-50">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                        </button>
                        <button wire:click="confirmServiceRemoval({{ $service->id }})" type="button" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-red-50">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>

                @if ($editingId === $service->id)
                    <div class="mt-6 bg-white border-2 border-primary/20 rounded-lg p-5 transition-all duration-300 shadow-lg">
                    @include('educator::livewire.profile.builder.partials.service-form')
                    <div class="flex justify-end gap-3 mt-4">
                        <button wire:click="resetForm" type="button" class="btn-secondary">@lang('educator::builder.cancel')</button>
                        <button wire:click="saveService" wire:loading.attr="disabled" wire:target="saveService" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg wire:loading wire:target="saveService" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>@lang('educator::builder.save_changes')</span>
                    </button>
                    </div>
                </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-500 py-8 border border-dashed rounded-lg">
                <p>@lang('educator::builder.services.no_services_added')</p>
            </div>
        @endforelse
    </div>

    @if ($showForm && !$editingId)
    <div class="mt-6 bg-white border-2 border-primary/50 rounded-lg p-5 transition-all duration-300 shadow-lg">
        <h3 class="font-bold text-lg text-foreground mb-4">@lang('educator::builder.services.add_new_service')</h3>
        @include('educator::livewire.profile.builder.partials.service-form')
        <div class="flex justify-end gap-3 mt-4 pt-4 border-t">
            <button wire:click="resetForm" type="button" class="btn-secondary">@lang('educator::builder.cancel')</button>
            <button wire:click="saveService" wire:loading.attr="disabled" wire:target="saveService" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="saveService" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.services.save_service')</span>
        </button>
        </div>
    </div>
    @endif

    @if (!$showForm)
    <button type="button" wire:click="showAddForm" class="mt-6 w-full border-2 border-dashed border-primary/30 hover:border-primary text-primary py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors flex items-center justify-center gap-2">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        @lang('educator::builder.services.add_new_service')
    </button>
    @endif

    <div class="flex justify-between pt-6 border-t mt-8">
        <button wire:click="$emitUp('goToPreviousStep')" type="button" class="btn-secondary">@lang('educator::builder.previous')</button>
        <button wire:click="saveAndNext" wire:loading.attr="disabled" wire:target="saveAndNext" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="saveAndNext" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.next')</span>
        </button>
    </div>
</div>
