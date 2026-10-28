<div class="tab-pane fade p-0 border-0" id="basicInfo-tab-pane"
     role="tabpanel" aria-labelledby="basicInfo-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateAuthPageContent" enctype="multipart/form-data">
            @error("auth_page_content_en")
            <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
            @enderror
            @error("auth_page_content_ar")
            <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
            @enderror

            <div class="row gy-3">
                <livewire:admin::settings.tabs.content.ar.quill :value_ar="$auth_page_content_ar">
            </div>



            <div class="row gy-3" style="margin-top: 6rem">
                <livewire:admin::settings.tabs.content.en.quill :value_en="$auth_page_content_en">
            </div>


            <div class="row gy-3" style="margin-top: 6rem">
                <div class="col-xl-6">
                    <label for="auth_page_image" class="form-label text-black">@lang('admin::home.Login/Register Image') <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="auth_page_image"  wire:model.defer="auth_page_image" accept="image/png, image/jpeg, image/webp">
                    <label for="auth_page_image" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                    @error("auth_page_image")
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-xl-2">
                    <img src="{{ $auth_page_image_path }}"
                         class="img-fluid rounded-start" alt="@lang('admin::home.Login/Register Image')" width="200" height="200">
                </div>
            </div>
            @AdminHasRole(['edit login/ register page content_settings'])
            <div class="row gy-3 mt-3">
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
