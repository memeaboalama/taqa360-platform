<div>
    <div class="modal fade" id="selectPackage" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.Select Package')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled" wire:target="attachPackage"></button>
                </div>
                <form wire:submit.prevent="attachPackage">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3 mt-2 ">
                                            @if(LaravelLocalization::getCurrentLocale() == "en")
                                            @if($terms_en)
                                            {!! $terms_en !!}
                                            @else
                                            <div class="text-center">
                                                <h6 class="fw-semibold">{{ $title['en'] ?? '' }}</h6>
                                                <p class="mb-0">{{ $description['en'] ?? '' }}</p>
                                                <p class="fs-15 fw-semibold mb-0">{{ $commission }} (%) @lang('marketplace::home.commission')</p>
                                            </div>
                                            @endif
                                            @elseif(LaravelLocalization::getCurrentLocale() == "ar")
                                            @if($terms_ar)
                                            {!! $terms_ar !!}
                                            @else
                                            <div class="text-center">
                                                <h6 class="fw-semibold">{{ $title['ar'] ?? '' }}</h6>
                                                <p class="mb-0">{{ $description['ar'] ?? '' }}</p>
                                                <p class="fs-15 fw-semibold mb-0">{{ $commission }} (%) @lang('marketplace::home.commission')</p>
                                            </div>
                                            @endif
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
                                <input class="form-check-input @error('terms_agreed') is-invalid @enderror" type="checkbox" value="1" id="terms_conditions" wire:model.defer="terms_agreed">
                                <label class="form-check-label text-muted" for="terms_conditions">
                                    @lang('marketplace::home.I agree with the')
                                </label><a class="fw-semibold text-primary ms-1"><u>@lang('marketplace::home.Terms & Conditions')</u></a>
                                @error('terms_agreed') <span class="d-block text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="btn-list mt-sm-0 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="attachPackage">@lang('marketplace::home.Close')
                            </button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="attachPackage">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="attachPackage">
                                    @lang('marketplace::home.agree')
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="attachPackage">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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