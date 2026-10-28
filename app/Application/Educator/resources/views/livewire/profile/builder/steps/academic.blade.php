<div>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0l15.482 0m-15.482 0L12 10.5M12 10.5l1.5-1.5m-1.5 1.5l-1.5-1.5m1.5 1.5V5.625" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.academic_info.title')</h2>
                <p class="text-gray-600">@lang('educator::builder.academic_info.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($academicProfiles as $profile)
            <div wire:key="qualification-item-wrapper-{{ $profile->id }}" class="bg-white border rounded-lg transition-all duration-300 {{ $editingId === $profile->id ? 'border-primary shadow-lg ring-2 ring-primary/20' : 'border-gray-200' }}">
                <div class="p-4 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 flex-grow min-w-0">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            @if ($profile->certificateFile)
                                <img src="{{ $profile->certificateFile->url }}" alt="Certificate" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-foreground truncate">{{ $profile->is_custom ? $profile->custom_name : $profile->approvedMajor?->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $profile->degree->trans() }} - {{ $profile->graduation_institution }} ({{ $profile->graduation_year }})</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button wire:click="edit({{ $profile->id }})" type="button" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-50">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                        </button>
                        <button wire:click="confirmQualificationRemoval({{ $profile->id }})" type="button" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-red-50">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a4.81 4.81 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 4.81 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
                @if ($editingId === $profile->id)
                <div class="border-t p-5">
                     @include('educator::livewire.profile.builder.partials.academic-form')
                </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-500 py-8 border border-dashed rounded-lg">
                <p>@lang('educator::builder.academic_info.no_qualifications_added')</p>
            </div>
        @endforelse
    </div>

    @if ($showForm && !$editingId)
    <div class="mt-6 bg-white border-2 border-primary/50 rounded-lg p-5">
        <h3 class="font-bold text-lg text-foreground mb-4">@lang('educator::builder.academic_info.add_new_qualification')</h3>
        @include('educator::livewire.profile.builder.partials.academic-form')
    </div>
    @endif

    @if (!$showForm)
    <button type="button" wire:click="showAddForm" class="mt-6 w-full border-2 border-dashed border-primary/30 hover:border-primary text-primary py-3 px-4 rounded-lg flex items-center justify-center gap-2">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        @lang('educator::builder.academic_info.add_new_qualification')
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
