<div class="tab-pane active show fade p-0 border-0" id="profile-tab-pane"
     role="tabpanel" aria-labelledby="profile-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateProfile" enctype="multipart/form-data">
            <div class="row gy-3">
                <div class="col-xl-6">
                    <label for="email" class="form-label text-default">{{ __('admin::home.Email Address') }} <span class="text-danger">*</span></label>
                    <input type="email"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           id="email"
                           wire:model.defer="email"
                           placeholder="Email Address">
                    @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-6">
                    <label for="name" class="form-label text-default">{{ __('admin::home.name') }} <span class="text-danger">*</span></label>
                    <input type="name"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           wire:model.defer="name"
                           placeholder="name">
                    @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @AdminHasRole(['edit profile_settings'])
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
