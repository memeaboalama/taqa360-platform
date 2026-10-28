<div>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.62-3.75M12 5.036V12m0 0h3.75M12 12L8.25 5.036" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.achievements')</h2>
                <p class="text-gray-600">@lang('educator::builder.achievements.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($achievements as $achievement)
            <div wire:key="achievement-wrapper-{{ $achievement->id }}" class="bg-white border rounded-lg transition-all duration-300 {{ $activeId === $achievement->id ? 'border-primary shadow-lg ring-2 ring-primary/20' : 'border-gray-200' }}">
                <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-grow w-full flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                            @switch($achievement->type)
                                @case('work_experience')
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-2V4a2 2 0 00-2-2h-4zM9 4h6v1H9V4z"></path></svg>
                                    @break
                                @case('certificate')
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 1a1 1 0 00-2 0v2.4A6.99 6.99 0 005.15 8.5L3.3 6.66a1 1 0 10-1.42 1.42l1.85 1.85A7.002 7.002 0 006 13.99V16a1 1 0 102 0v-2.01a7.002 7.002 0 005.14-5.14l1.85 1.85a1 1 0 101.42-1.42L14.56 8.5A6.99 6.99 0 0013 3.4V1zm-2 4.41A5.002 5.002 0 0115.59 11a5.002 5.002 0 01-4.58 4.59A5.002 5.002 0 016.41 11 5.002 5.002 0 0111 6.41zM11 9a2 2 0 100 4 2 2 0 000-4z"></path><path d="M18 16a1 1 0 100 2h-4a1 1 0 100 2h4a3 3 0 100-6h-4a1 1 0 100 2h4z"></path></svg>
                                    @break
                                @case('course')
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm2 0v12h12V6H6zm7 2.75a.75.75 0 00-1.5 0v3.5a.75.75 0 001.5 0v-3.5zM12 15a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                                    @break
                                @case('workshop')
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 10a1 1 0 01-2 0c0-.83.67-1.5 1.5-1.5.82 0 1.5.67 1.5 1.5zM9.5 8.5c.83 0 1.5.67 1.5 1.5a1 1 0 11-2 0c0-.83.67-1.5 1.5-1.5zM6 10a1 1 0 11-2 0c0-.83.67-1.5 1.5-1.5S6 9.17 6 10zm4.5 3.5c-.83 0-1.5-.67-1.5-1.5a1 1 0 112 0c0 .83-.67 1.5-1.5 1.5zm3.5 1.5a1 1 0 100-2c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5z"></path><path d="M4 2a2 2 0 00-2 2v16a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2H4zm14.5 15.5a.5.5 0 01-.5.5H6a.5.5 0 01-.5-.5V15h13v2.5zM19 13H5V6.5a.5.5 0 01.5-.5h13a.5.5 0 01.5.5V13z"></path></svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="font-bold text-foreground truncate">{{ $achievement->title }}</p>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-x-4 gap-y-1 text-sm text-gray-500 mt-1">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                                    <span class="truncate">{{ $achievementTypes[$achievement->type] ?? $achievement->type }}</span>
                                </span>
                                <span class="text-gray-300 hidden sm:inline">|</span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18" /></svg>
                                    <span class="whitespace-nowrap">{{ \Carbon\Carbon::parse($achievement->start_date)->format('Y-m') }}</span>
                                    @if($achievement->type == 'work_experience' && $achievement->end_date)
                                        <span class="text-gray-400">-</span>
                                        <span class="whitespace-nowrap">{{ \Carbon\Carbon::parse($achievement->end_date)->format('Y-m') }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 w-full md:w-auto flex items-center justify-end md:justify-center gap-2 mt-4 md:mt-0 border-t md:border-none pt-4 md:pt-0">
                        <button wire:click="selectForEditing({{ $achievement->id }})" type="button" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-50" title="@lang('educator::builder.edit')">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                        </button>
                        <button wire:click="confirmRemoval({{ $achievement->id }})" type="button" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-red-50" title="@lang('educator::builder.delete')">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a4.81 4.81 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 4.81 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
                @if ($activeId === $achievement->id)
                    <div class="border-t border-gray-200 p-4">
                        @include('educator::livewire.profile.builder.partials.achievement-form')
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-500 py-8 border border-dashed rounded-lg">
                <p>@lang('educator::builder.achievements.no_achievements_added')</p>
            </div>
        @endforelse
    </div>
    <div class="mt-6">
        @if ($showAddForm)
            <div class="bg-white border-2 border-primary/50 rounded-lg p-5">
                <h3 class="font-bold text-lg text-foreground mb-4">@lang('educator::builder.achievements.add_new_achievement')</h3>
                @include('educator::livewire.profile.builder.partials.achievement-form')
            </div>
        @else
            <button type="button" wire:click="showAddForm" class="w-full border-2 border-dashed border-gray-300 hover:border-primary text-gray-600 hover:text-primary py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                @lang('educator::builder.achievements.add_new_achievement')
            </button>
        @endif
    </div>
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
