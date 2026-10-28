<div class="tab-pane fade p-0 border-0" id="basicUserInfo-tab-pane"
     role="tabpanel" aria-labelledby="basicUserInfo-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateUserPageContent" enctype="multipart/form-data">
            @error("user_page_content_en")
            <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
            @enderror
            @error("user_page_content_ar")
            <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
            @enderror

            <div class="gy-3">
                <livewire:admin::settings.tabs.content-user.ar.quill :value_user_ar="$user_page_content_ar">
            </div>
            <div class="gy-3" style="margin-top: 6rem">
                <livewire:admin::settings.tabs.content-user.en.quill :value_user_en="$user_page_content_en">
            </div>
            @AdminHasRole(['edit login/ register page content_settings'])
            <div class="gy-3 mt-3" style="display: contents !important;">
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-md btn-primary px-4">
                        <span class="indicator-label" wire:loading.class="d-none">
                            @lang('admin::home.Save')
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                            @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
            @endAdminHasRole
        </form>
    </div>

</div>
