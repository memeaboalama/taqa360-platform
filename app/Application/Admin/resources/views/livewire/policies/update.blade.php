<div class="mt-1">
    <form method="POST" wire:submit.prevent="updatePolicies">
        @error("policies_ar")
        <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
        @enderror

        @error("policies_en")
        <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
        @enderror
        <div class="row">
            <livewire:admin::policies.ar.quill :value_ar="$policies_ar">
        </div>

        <div class="row" style="margin-top: 6rem">
            <livewire:admin::policies.en.quill :value_en="$policies_en">
        </div>
        @AdminHasRole(['edit_privacy policy'])
            <div class="row gy-3 mt-3" style="margin-top: 6rem !important;">
                <div class="col-xl-2 col-md-3 col-12 mx-auto d-grid mt-4">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="updatePolicies">
                        <span class="indicator-label" wire:loading.class="d-none" wire:target="updatePolicies">
                            @lang('admin::lang.Save')
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="updatePolicies">
                            @lang('admin::lang.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        @endAdminHasRole
    </form>
</div>
