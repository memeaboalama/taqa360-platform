<div>
    {{-- Step Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.skills')</h2>
                <p class="text-gray-600">@lang('educator::builder.skills.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="space-y-1" x-data="{ open: false }" @click.away="open = false">
        <label class="form-label">@lang('educator::builder.skills.add_skill') *</label>
        <div class="relative">
            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </div>
            <input
                wire:model.debounce.300ms="search"
                wire:keydown.enter.prevent="addCustomSkill"
                wire:focus="showSuggestions"
                @focus="open = true"
                type="text"
                class="form-input {{ app()->getLocale() == 'ar' ? 'pr-10' : 'pl-10' }}"
                placeholder="@lang('educator::builder.skills.search_placeholder')"
                autocomplete="off"
            >
        </div>
        <p class="text-xs text-gray-500 mt-1">@lang('educator::builder.skills.search_helper')</p>
        @error('search') <span class="form-error">{{ $message }}</span> @enderror

        <div x-show="open" x-cloak class="relative">
            @if(($searchResults && $searchResults->isNotEmpty()) || (strlen($search) >= 2))
            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                @if($searchResults->isNotEmpty())
                    @foreach($searchResults as $skill)
                    <div wire:click="addSkill({{ $skill->id }})" class="dropdown-item flex justify-between items-center">
                        <span>{{ $skill->name }}</span>
                        <span class="text-xs text-primary font-semibold">@lang('educator::builder.skills.select')</span>
                    </div>
                    @endforeach
                @elseif(strlen($search) >= 2)
                    <div class="dropdown-item text-gray-500">
                        @lang('educator::builder.skills.no_results')
                    </div>
                @endif

                @if(strlen($search) >= 2)
                <div wire:click="addCustomSkill" class="dropdown-item flex justify-between items-center text-green-600 border-t border-gray-100">
                    <span>@lang('educator::builder.skills.add_custom_skill', ['skill' => $search])</span>
                    <span class="text-xs font-semibold">@lang('educator::builder.skills.add')</span>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <h3 class="font-semibold text-foreground mb-4">@lang('educator::builder.skills.added_skills')</h3>
        @if(!empty($educatorSkills))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($educatorSkills as $index => $skill)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3 relative" wire:key="skill-display-{{ $skill['id'] }}">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-foreground truncate">{{ $skill['name'] }}</span>
                            <button wire:click="confirmSkillRemoval({{ $skill['id'] }})" type="button" class="text-gray-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div x-data="{ level: @entangle('educatorSkills.'.$index.'.level'), hoverLevel: 0 }" class="space-y-2">
                            <div class="flex justify-between items-center">
                                <label class="text-sm font-medium text-gray-700">@lang('educator::builder.skills.proficiency_level')</label>
                                <span class="text-sm font-semibold text-primary" x-text="hoverLevel > 0 ? getLevelLabel(hoverLevel) : getLevelLabel(level)">
                                    {{ $this->getLevelLabel($skill['level']) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between" @mouseleave="hoverLevel = 0">
                                @foreach(range(1,5) as $dot)
                                <button
                                    type="button"
                                    @mouseenter="hoverLevel = {{ $dot }}"
                                    @click="level = {{ $dot }}"
                                    class="w-full h-2 rounded-full mx-0.5 transition-colors duration-200"
                                    :class="{
                                        'bg-primary': (hoverLevel > 0 && {{ $dot }} <= hoverLevel) || (hoverLevel === 0 && {{ $dot }} <= level),
                                        'bg-gray-200': (hoverLevel > 0 && {{ $dot }} > hoverLevel) || (hoverLevel === 0 && {{ $dot }} > level)
                                    }">
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-8 border border-dashed rounded-lg">
                <p>@lang('educator::builder.skills.no_skills_added')</p>
            </div>
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
