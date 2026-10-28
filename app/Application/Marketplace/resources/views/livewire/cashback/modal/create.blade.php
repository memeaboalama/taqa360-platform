<div>
    <div class="modal fade" id="createMerchantCashback"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{__('admin::home.Create New Cashback')}}</h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">

                                            <div class="col-xl-4">
                                                <label for="name" class="form-label text-black">{{__('admin::home.Name')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error("name") is-invalid @enderror"
                                                       id="name"
                                                       placeholder="CASHBACK" wire:model.defer="name">

                                                @error("name") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="type" class="form-label text-black">{{__('admin::home.Usage Lift')}} <span class="text-danger">*</span></label>
                                                <input type="number" step="1" min="0" class="form-control @error("usages_left") is-invalid @enderror"
                                                       id="usages_left"
                                                       placeholder="0" wire:model.defer="usages_left">

                                                @error("usages_left") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="type" class="form-label text-black">{{__('admin::home.limit per user')}} <span class="text-danger">*</span></label>
                                                <input type="number" step="1" min="1" class="form-control @error("limit_per_user") is-invalid @enderror"
                                                       id="limit_per_user"
                                                       placeholder="0" wire:model.defer="limit_per_user">

                                                @error("limit_per_user") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="type" class="form-label text-black">{{__('admin::home.Min Order Value')}} <span class="text-danger">*</span></label>
                                                <input type="number" step="0.005" min="0" class="form-control @error("min_order_value") is-invalid @enderror"
                                                       id="min_order_value"
                                                       placeholder="0" wire:model.defer="min_order_value">

                                                @error("min_order_value") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="type" class="form-label text-black">{{__('admin::home.Type')}} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('type') is-invalid @enderror"  wire:model.defer="type">
                                                    <option value="">{{__('admin::home.Select Type')}}</option>
                                                    <option value="percent">{{__('admin::home.Percent')}}</option>
                                                    <option value="flat">{{__('admin::home.Flat')}}</option>
                                                </select>

                                                @error("type") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>


                                            <div class="col-xl-4">
                                                <label for="max_discount" class="form-label text-black">{{__('admin::home.Discount Value')}} <span class="text-danger">*</span></label>
                                                <input type="number" step="0.5" min="0" class="form-control @error("discount_value") is-invalid @enderror"
                                                       id="discount_value"
                                                       placeholder="0" wire:model.defer="discount_value">

                                                @error("discount_value") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="max_discount" class="form-label text-black">{{__('admin::home.Max Discount')}} <span class="text-danger">*</span></label>
                                                <input type="number" step="0.5" min="0" class="form-control @error("max_discount") is-invalid @enderror"
                                                id="min_order_value" placeholder="0" wire:model.defer="max_discount">
                                                
                                                @error("max_discount") <span
                                                class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <label for="expired_at" class="form-label text-black">{{__('admin::home.Expire At')}} <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @error("expired_at") is-invalid @enderror"
                                                       id="expired_at" wire:model.defer="expired_at">

                                                @error("expired_at") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>
                                                                          
                                            
                                            <div class="col-xl-1">
                                                <label for="category-active" class="form-label text-black">{{__('admin::home.status')}} <span class="text-danger">*</span></label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-1 mt-3">
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
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">{{__('admin::home.Close')}}
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    {{__('admin::home.Save')}}
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    {{__('admin::home.Wait')}}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
