<div>
    <div class="modal fade" id="selectPackage" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('driver::home.Select Package')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="attachPackage">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3 mt-2 " >
                                            @if(LaravelLocalization::getCurrentLocale() == "en")
                                                {!! $terms_en !!}
                                            @elseif(LaravelLocalization::getCurrentLocale() == "ar")
                                                {!! $terms_ar !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$selected_package)
                    <div class="modal-footer d-sm-flex d-block align-items-center justify-content-between shadow-lg">
                        <div>
                            <div class="form-check d-block">
                                <input class="form-check-input" type="checkbox" value="" id="terms_conditions" required>
                                <label class="form-check-label text-muted" for="terms_conditions">
                                    @lang('driver::home.I agree with the')
                                </label><a class="fw-semibold text-primary ms-1"><u>@lang('driver::home.Terms & Conditions')</u></a>
                            </div>
                        </div>
                        <div class="btn-list mt-sm-0 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('driver::home.Close')
                            </button>
                            <button type="submit" class="btn btn-success">
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('driver::home.agree')
                            </span>
                                <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                            </button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

</div>
