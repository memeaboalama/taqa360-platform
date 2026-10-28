<div class="mb-4">
    <label class="form-label">{{ __('admin::lang.Select Page') }}</label>
    <select class="form-select form-select-lg" wire:model="selectedPageId">
        @foreach($pages as $page)
            <option value="{{ $page->id }}">
                {{ $page->getTranslation('name', app()->getLocale()) }}
            </option>
        @endforeach
    </select>
</div>
