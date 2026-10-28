<div>
    <div class="modal fade" id="enablePromocode"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Enable Coupon')</h6>
                    <button type="button" class="btn-close" id="enablePromocodeCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="enable"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="enable">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="container">
                                        <div class="text-center my-4">
                                            {{__('admin::home.The coupon will be activated within')}}
                                            {{$period}}
                                            {{__('admin::home.days from today\'s date')}}
                                            </div>
                                            <div class="text-center text-success my-4">
                                            {{__('admin::home.Are you sure to activate the coupon?')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" id="enablePromocodeCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="enable">{{__('admin::home.Close')}}
                        </button>
                        <button type="submit" class="btn btn-success" id="enablePromocodeSubmitBtn"
                                wire:loading.attr="disabled" wire:target="enable">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="enable">
                                    {{__('admin::home.Enable')}}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="enable">
                                    {{__('admin::home.Wait')}} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
