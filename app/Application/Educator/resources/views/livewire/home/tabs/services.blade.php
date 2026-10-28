<div>
    @if ($showFormModal)
        <div style="position: fixed; inset: 0; z-index: 999999;" wire:loading.class="pointer-events-none" wire:target="saveService">
            <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold" style="color: #333333;">{{ $editingId ? __('educator::builder.services.edit_service') : __('educator::builder.services.add_new_service') }}</h3>
                        <button type="button" wire:click="closeForm" wire:loading.attr="disabled" wire:target="saveService" class="text-gray-500 hover:text-gray-800 text-2xl leading-none disabled:opacity-50 disabled:cursor-not-allowed">&times;</button>
                    </div>

                    <div class="px-5 py-4 space-y-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::builder.services.service_type') *</label>
                            <select wire:model="formData.service_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" style="color: #333333;">
                                <option value="">@lang('educator::builder.services.select_service_type')</option>
                                @foreach($approvedServices as $approved)
                                    <option value="{{ $approved->id }}">{{ $approved->name }}</option>
                                @endforeach
                                <option value="custom">@lang('educator::builder.services.is_custom_service')</option>
                            </select>
                            @error('formData.service_id') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @if($formData['is_custom'] ?? false)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::builder.services.custom_service_details') *</label>
                                <input wire:model.defer="formData.custom_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="@lang('educator::builder.services.custom_service_placeholder')">
                                @error('formData.custom_name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="space-y-1">
                            <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::builder.services.service_description') *</label>
                            <textarea wire:model.defer="formData.custom_description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="@lang('educator::builder.services.service_description_placeholder')"></textarea>
                            @error('formData.custom_description') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-5 py-4 flex justify-end gap-3 rounded-b-lg">
                        <button type="button" wire:click="closeForm" wire:loading.attr="disabled" wire:target="saveService" class="border border-gray-300 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            @lang('educator::builder.cancel')
                        </button>
                        <button wire:click="saveService" wire:loading.attr="disabled" wire:target="saveService" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: #306FAA; color: #ffffff;">
                            <svg wire:loading wire:target="saveService" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ $editingId ? __('educator::builder.services.edit_service') : __('educator::builder.services.save_service') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div style="position: fixed; inset: 0; z-index: 999999;" wire:loading.class="pointer-events-none" wire:target="executeDelete">
            <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="p-6 text-center">
                        <div class="mx-auto flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
                            <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-bold" style="color: #333333;">@lang('educator::home.services.delete')</h3>
                            <p class="text-sm text-gray-600 mt-2">@lang('educator::home.services.delete_confirm_message')</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-center sm:gap-3 rounded-b-lg">
                        <button wire:click="executeDelete" wire:loading.attr="disabled" wire:target="executeDelete" type="button" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="executeDelete" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>@lang('educator::builder.yes_delete')</span>
                        </button>
                        <button wire:click="closeDelete" wire:loading.attr="disabled" wire:target="executeDelete" type="button" class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 border border-gray-300 hover:bg-gray-100 transition-colors mt-2 sm:mt-0 disabled:opacity-50 disabled:cursor-not-allowed">
                            @lang('educator::builder.cancel')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-xl font-semibold" style="color: #333333;">@lang('educator::home.services.manage_services')</h3>
            <button wire:click="showAddForm" class="px-4 py-2 rounded-lg transition-colors" style="background-color: #306FAA; color: #ffffff;">
                @lang('educator::home.services.add_new_service')
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($services as $service)
                <div wire:key="service-card-{{ $service->id }}" class="border border-gray-200 rounded-lg p-6">
                    <h4 class="font-semibold text-lg mb-2" style="color: #333333;">{{ $service->is_custom ? $service->custom_name : $service->approvedService?->name }}</h4>
                    <p class="text-gray-600 mb-3 text-sm">{{ $service->custom_description }}</p>
                    <div class="flex justify-end items-center mb-4">
                        <span class="text-sm px-2 py-1 rounded {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                            {{ $service->is_active ? __('educator::home.services.status_active') : __('educator::home.services.status_inactive') }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="edit({{ $service->id }})" wire:loading.attr="disabled" wire:target="edit" class="inline-flex items-center gap-1 px-3 py-1 rounded text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: #306FAA; color: #ffffff;">
                            <span wire:loading.remove wire:target="edit">@lang('educator::home.services.edit')</span>
                            <svg wire:loading wire:target="edit" class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        <button wire:click="toggleStatus({{ $service->id }})" wire:loading.attr="disabled" wire:target="edit" class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" style="color: #333333;">
                            {{ $service->is_active ? __('educator::home.services.deactivate') : __('educator::home.services.activate') }}
                        </button>
                        <button wire:click="confirmDelete({{ $service->id }})" wire:loading.attr="disabled" wire:target="edit" class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" style="color: #333333;">@lang('educator::home.services.delete')</button>
                    </div>
                </div>
            @empty
                <p class="md:col-span-2 text-center text-gray-500 py-8">@lang('educator::home.services.no_services_added')</p>
            @endforelse
        </div>
    </div>
</div>