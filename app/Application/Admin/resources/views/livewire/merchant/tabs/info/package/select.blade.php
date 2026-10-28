<div>
    <div class="modal fade" id="selectPackage" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.Select Package')</h6>
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
                    <div class="modal-footer d-sm-flex d-block align-items-center justify-content-between shadow-lg">
                        <div class="btn-list mt-sm-0 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('marketplace::home.Close')
                            </button>
                            <button type="submit" class="btn btn-success">
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('marketplace::home.Save')
                            </span>
                                <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
