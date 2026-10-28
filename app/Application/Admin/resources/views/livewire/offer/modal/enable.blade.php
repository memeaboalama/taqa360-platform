<div>
    <div class="modal fade" id="enableOffer"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Enable Offer')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                                            {{__('admin::home.The offer will be activated within')}}
                                            {{$period}}
                                            {{__('admin::home.days from today\'s date')}}
                                            </div>
                                            <div class="alert bg-success text-white text-center my-4">
                                            {{__('admin::home.Are you sure to activate the offer?')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success"
                                data-bs-dismiss="modal">{{__('admin::home.Close')}}
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    {{__('admin::home.Enable')}}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                    {{__('admin::home.Wait')}} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
