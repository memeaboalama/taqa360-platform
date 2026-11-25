<div class="card custom-card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('admin::home.Technical Support') }}</h5>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="save" novalidate>
            <div class="row gy-3">
                <div class="col-xl-6">
                    <label for="support_email" class="form-label text-default">
                        {{ __('admin::home.Support Email') }}
                    </label>
                    <input type="text"
                           class="form-control form-control-lg @error('support_email') is-invalid @enderror"
                           id="support_email"
                           wire:model.defer="support_email"
                           inputmode="email"
                           placeholder="{{ __('admin::home.Support Email') }}"
                           @disabled(!$canEdit)>
                    @error('support_email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-xl-6">
                    <label for="support_phone" class="form-label text-default">
                        {{ __('admin::home.Support Phone') }}
                    </label>
                    <input type="text"
                           class="form-control form-control-lg @error('support_phone') is-invalid @enderror"
                           id="support_phone"
                           wire:model.defer="support_phone"
                           placeholder="{{ __('admin::home.Support Phone') }}"
                           @disabled(!$canEdit)>
                    @error('support_phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @if($canEdit)
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-md btn-primary px-4">
                        <span class="indicator-label" wire:loading.class="d-none">
                            @lang('admin::home.Save')
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                            @lang('admin::home.Wait')
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
