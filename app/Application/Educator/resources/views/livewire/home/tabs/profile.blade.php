<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h3 class="text-xl font-semibold text-gray-800">@lang('educator::home.profile.personal_data')</h3>
      <div class="flex items-center gap-3">
        @if($editMode)
            <button wire:click="toggleEditMode" class="btn-secondary">@lang('educator::home.profile.cancel')</button>
            <button wire:click="save" class="btn-primary">@lang('educator::home.profile.save')</button>
        @else
            <button wire:click="toggleEditMode" class="btn-primary">@lang('educator::home.profile.edit_data')</button>
        @endif
      </div>
    </div>
    <div class="space-y-6">
      <div class="bg-white border border-gray-200 rounded-lg p-6">
          <h4 class="font-semibold mb-4 text-gray-700">@lang('educator::home.profile.basic_info')</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                  <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::home.profile.full_name')</label>
                  @if($editMode)
                      <input type="text" wire:model.defer="name" class="form-input w-full">
                      @error('name') <span class="form-error">{{ $message }}</span> @enderror
                  @else
                      <p class="text-gray-800">{{ $name }}</p>
                  @endif
              </div>
              <div>
                  <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::home.profile.job_title')</label>
                  @if($editMode)
                      <input type="text" wire:model.defer="specialization" class="form-input w-full">
                      @error('specialization') <span class="form-error">{{ $message }}</span> @enderror
                  @else
                      <p class="text-gray-800">{{ $specialization }}</p>
                  @endif
              </div>
              <div>
                  <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::home.sidebar.location')</label>
                  @if($editMode)
                      <input type="text" wire:model.defer="city_id" class="form-input w-full">
                      @error('city_id') <span class="form-error">{{ $message }}</span> @enderror
                  @else
                      <p class="text-gray-800">{{ $city_id }}</p>
                  @endif
              </div>
              <div>
                  <label class="block text-sm font-medium mb-1 text-gray-600">@lang('educator::home.profile.experience')</label>
                  @if($editMode)
                      <input type="number" wire:model.defer="experience_years" class="form-input w-full">
                      @error('experience_years') <span class="form-error">{{ $message }}</span> @enderror
                  @else
                      <p class="text-gray-800">{{ $experience_years }}</p>
                  @endif
              </div>
          </div>
      </div>
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="font-semibold mb-4 text-gray-700">@lang('educator::home.profile.bio')</h4>
        @if($editMode)
            <textarea wire:model.defer="description" rows="5" class="form-input w-full"></textarea>
            @error('description') <span class="form-error">{{ $message }}</span> @enderror
        @else
            <p class="text-gray-600 leading-relaxed prose prose-sm max-w-none">{{ $description }}</p>
        @endif
      </div>
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-gray-700">@lang('educator::home.profile.skills')</h4>
            <a href="{{ route('educator:profile.builder', ['step' => 'skills']) }}" wire:navigate class="text-sm text-primary hover:underline">@lang('educator::home.profile.manage_skills')</a>
        </div>
        <div class="flex flex-wrap gap-2">
            @forelse($educator->skills as $skill)
                <span class="badge-light">{{ $skill->is_custom ? $skill->custom_name : $skill->approvedSkill?->name }}</span>
            @empty
                <p class="text-sm text-gray-500">@lang('educator::home.profile.no_skills_added')</p>
            @endforelse
        </div>
      </div>
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-gray-700">@lang('educator::home.profile.languages')</h4>
            <a href="{{ route('educator:profile.builder', ['step' => 'languages']) }}" wire:navigate class="text-sm text-primary hover:underline">@lang('educator::home.profile.manage_languages')</a>
        </div>
        <div class="space-y-3">
            @forelse($educator->languages as $lang)
                <div class="flex justify-between items-center">
                    <span class="text-gray-800">{{ $lang->language?->title }}</span>
                    <span class="badge-light">{{ __('educator::home.profile.levels.' . $lang->level) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-500">@lang('educator::home.profile.no_languages_added')</p>
            @endforelse
        </div>
      </div>
    </div>
</div>
