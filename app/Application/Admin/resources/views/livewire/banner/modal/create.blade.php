<div>
    <div class="modal fade" id="createBanner"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Create New Banner')</h6>
                    <button type="button" class="btn-close" id="createBannerCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="store"
                            aria-label="Close"></button>
                </div>
                <form method="POST" wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-10">
                                                <label for="image" class="form-label text-black">@lang('admin::lang.Image') <span class="text-danger">*</span></label>
                                                <input
                                                    type="file"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    id="image"
                                                    placeholder="{{ __('admin::lang.Image') }}"
                                                    accept="image/png, image/jpeg, image/gif"
                                                    wire:model="image">
                                                @error("image")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-xl-2">
                                                <label for="category-active"
                                                       class="form-label text-black">@lang('admin::lang.Status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="category-active" class="label-success"
                                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="createBannerCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="store">@lang('admin::lang.Close')
                        </button>
                        <button type="submit" class="btn btn-success" id="createBannerSubmitBtn"
                                wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    @lang('admin::lang.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    @lang('admin::lang.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
