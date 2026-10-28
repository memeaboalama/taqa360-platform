<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header border-bottom">
                 <h3 class="card-title">{{ __('admin::lang.Footer Settings') }}</h3>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">

                    <div class="mb-5">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.General Info') }}</h6>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Footer Logo') }}</label>
                                <div class="position-relative border border-dashed rounded bg-gray-50 text-center d-flex align-items-center justify-content-center hover-bg-light transition-all" style="height: 120px;">
                                    @if($logo)
                                        <div class="position-absolute align-items-center justify-content-center w-100 h-100 p-2 d-flex bg-white rounded">
                                            <img src="{{ $logo->temporaryUrl() }}" class="mw-100 mh-100" style="object-fit: contain;">
                                        </div>
                                    @elseif(!empty($existing_logo))
                                        <div class="position-absolute align-items-center justify-content-center w-100 h-100 p-2 d-flex bg-white rounded">
                                            <img src="{{ Storage::url($existing_logo) }}" class="mw-100 mh-100" style="object-fit: contain;">
                                        </div>
                                    @else
                                        <div class="text-muted pointer-events-none">
                                            <i class="fe fe-image fs-24 mb-1"></i>
                                            <div class="tx-11">{{ __('admin::lang.Upload Logo') }}</div>
                                        </div>
                                    @endif

                                    <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" wire:model="logo" accept="image/*">
                                    
                                    @if(($logo || !empty($existing_logo)) && !$delete_logo)
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 start-0 m-1 rounded-circle shadow p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; z-index: 10;" wire:click.prevent="removeImage">
                                            <i class="fe fe-x tx-12"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Description') }} (EN)</label>
                                        <textarea class="form-control form-control-sm" rows="4" wire:model.defer="footer_description_en"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Description') }} (AR)</label>
                                        <textarea class="form-control form-control-sm" rows="4" wire:model.defer="footer_description_ar" dir="rtl"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Contact Information') }}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Phone Number') }}</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Email Address') }}</label>
                                <input type="email" class="form-control form-control-sm" wire:model.defer="email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Address') }} (EN)</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="address_en">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Address') }} (AR)</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="address_ar" dir="rtl">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Social Media') }}</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fe fe-facebook"></i></span>
                                    <input type="url" class="form-control" placeholder="Facebook URL" wire:model.defer="social_facebook">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fe fe-twitter"></i></span>
                                    <input type="url" class="form-control" placeholder="Twitter/X URL" wire:model.defer="social_twitter">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fe fe-instagram"></i></span>
                                    <input type="url" class="form-control" placeholder="Instagram URL" wire:model.defer="social_instagram">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fe fe-linkedin"></i></span>
                                    <input type="url" class="form-control" placeholder="LinkedIn URL" wire:model.defer="social_linkedin">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fe fe-youtube"></i></span>
                                    <input type="url" class="form-control" placeholder="YouTube URL" wire:model.defer="social_youtube">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fa fa-whatsapp"></i></span>
                                    <input type="url" class="form-control" placeholder="WhatsApp URL" wire:model.defer="social_whatsapp">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Important Links') }} & {{ __('admin::lang.Copyright') }}</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Terms & Conditions') }} URL</label>
                                <input type="url" class="form-control form-control-sm" wire:model.defer="link_terms">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Privacy Policy') }} URL</label>
                                <input type="url" class="form-control form-control-sm" wire:model.defer="link_privacy">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Contact Us') }} URL</label>
                                <input type="url" class="form-control form-control-sm" wire:model.defer="link_contact">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Copyright Text') }} (EN)</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="copyright_text_en">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Copyright Text') }} (AR)</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="copyright_text_ar" dir="rtl">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <span wire:loading.remove wire:target="save">
                                <i class="fe fe-save me-1"></i> {{ __('admin::lang.Save Settings') }}
                            </span>
                             <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1"></span> {{ __('admin::lang.Saving...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
