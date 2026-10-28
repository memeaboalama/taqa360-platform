<div>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.documents')</h2>
                <p class="text-gray-600">@lang('educator::builder.documents.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($documents as $index => $document)
            <div class="bg-white border border-gray-200 rounded-lg p-5 relative transition-all duration-300 hover:shadow-md"
                wire:key="document-{{ $index }}">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg text-foreground">الوثيقة #{{ $loop->iteration }}</h3>
                    @if(count($documents) > 0)
                        <button wire:click="removeDocument({{ $index }})" type="button"
                            class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label
                            class="block text-sm font-medium text-gray-700">@lang('educator::builder.documents.document_type')
                            *</label>
                        <select wire:model="documents.{{ $index }}.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                            <option value="">@lang('educator::builder.documents.select_document_type')</option>
                            @foreach($documentTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('documents.' . $index . '.type') <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-sm font-medium text-gray-700">@lang('educator::builder.documents.document_name')
                            *</label>
                        <input wire:model.defer="documents.{{ $index }}.file_name" type="text"
                            placeholder="@lang('educator::builder.documents.document_name_placeholder')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                        @error('documents.' . $index . '.file_name') <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label
                            class="block text-sm font-medium text-gray-700">@lang('educator::builder.documents.upload_file')
                            *</label>
                        <input wire:model="uploads.{{ $index }}" type="file" id="doc-upload-{{$index}}"
                            class="block w-full text-sm text-gray-500 file:mr-4 rtl:file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        @error('uploads.' . $index) <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    @if(!empty($document['id']) && !empty($document['url']))
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <svg class="w-5 h-5 text-primary shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32a.75.75 0 01-1.06-1.06L16.51 8.3" /></svg>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $document['original_name'] }}</p>
                                        <p class="text-xs text-gray-500">@lang('educator::builder.documents.current_file')</p>
                                    </div>
                                </div>
                                <a href="{{ $document['url'] }}" target="_blank" class="text-sm font-medium text-primary hover:underline shrink-0">@lang('educator::builder.documents.view_file')</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 py-8">@lang('educator::builder.documents.no_documents_added')</p>
        @endforelse
    </div>

    <button type="button" wire:click="addDocument"
        class="mt-6 w-full border-2 border-dashed border-primary/30 hover:border-primary text-primary py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors flex items-center justify-center gap-2">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        @lang('educator::builder.documents.add_new_document')
    </button>

    <div class="flex justify-between pt-6 border-t mt-8">
        <button wire:click="$emitUp('goToPreviousStep')" type="button"
            class="px-8 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-semibold">@lang('educator::builder.previous')</button>
        <button wire:click="save" wire:loading.attr="disabled" wire:target="save" type="button"
            class="px-8 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-semibold inline-flex items-center justify-center gap-2">
            <svg wire:loading wire:target="save" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.next')</span>
        </button>
    </div>
</div>
