<div>
    <div class="modal fade" id="updatePromocodeAdminMerchants"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{__('admin::home.Update Promocode')}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">

                                            <div class="col-xl-6">
                                                <label for="code" class="form-label text-black">{{__('admin::home.Code')}}</label>
                                                <input type="text" class="form-control @error("code") is-invalid @enderror"
                                                       id="code"
                                                       placeholder="PROMOCODE" wire:model.defer="code">

                                                @error("code") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-3">
                                                <label for="type" class="form-label text-black">{{__('admin::home.Usage Lift')}}</label>
                                                <input type="number" step="1" min="0" class="form-control @error("usages_left") is-invalid @enderror"
                                                       id="usages_left"
                                                       placeholder="0" wire:model.defer="usages_left">

                                                @error("usages_left") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-3">
                                                <label for="type" class="form-label text-black">{{__('admin::home.limit per user')}}</label>
                                                <input type="number" step="1" min="1" class="form-control @error("limit_per_user") is-invalid @enderror"
                                                       id="limit_per_user"
                                                       placeholder="0" wire:model.defer="limit_per_user">

                                                @error("limit_per_user") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-3">
                                                <label for="type" class="form-label text-black">{{__('admin::home.Min Order Value')}}</label>
                                                <input type="number" step="0.005" min="0" class="form-control @error("min_order_value") is-invalid @enderror"
                                                       id="min_order_value"
                                                       placeholder="0" wire:model.defer="min_order_value">

                                                @error("min_order_value") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="type" class="form-label text-black">{{__('admin::home.Type')}}</label>
                                                <select class="form-control @error('type') is-invalid @enderror" wire:model.defer="type">
                                                    <option value="">Select Type</option>
                                                    <option value="percent">Percent</option>
                                                    <option value="flat">Flat</option>
                                                </select>

                                                @error("type") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>


                                            <div class="col-xl-4">
                                                <label for="max_discount" class="form-label text-black">{{__('admin::home.Discount Value')}}</label>
                                                <input type="number" step="0.5" min="0" class="form-control @error("discount_value") is-invalid @enderror"
                                                       id="discount_value"
                                                       placeholder="0" wire:model.defer="discount_value">

                                                @error("discount_value") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>

                                            <div class="col-xl-4">
                                                <label for="max_discount" class="form-label text-black">{{__('admin::home.Max Discount')}}</label>
                                                <input type="number" step="0.5" min="0" class="form-control @error("max_discount") is-invalid @enderror"
                                                       id="min_order_value"
                                                       placeholder="0" wire:model.defer="max_discount">
                                                @error("max_discount") <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <label class="form-label text-black">{{__('admin::home.Sales Agents')}}</label>
                                                <livewire:admin::promocode.search.search />
                                                @error('district_id') <span class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="expired_at" class="form-label text-black">{{__('admin::home.Expire At')}}</label>
                                                <input type="date" class="form-control @error("expired_at") is-invalid @enderror"
                                                       id="expired_at" wire:model.defer="expired_at">

                                                @error("date") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>
                                            <div class="col-xl-4">
                                                <label for="type_discount" class="form-label text-black">{{__('admin::home.Discount type')}}</label>
                                                <select class="form-control @error('type_discount') is-invalid @enderror"  wire:model="type_discount">
                                                    <option value="">{{__('admin::home.Select Discount type')}}</option>
                                                    <option value="order">{{__('admin::home.Order')}}</option>
                                                    <option value="delivery">{{__('admin::home.Delivery')}}</option>
                                                </select>

                                                @error("type_discount") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div>
                                            {{-- <div class="col-xl-4">
                                                <label for="is_custom" class="form-label text-black">{{__('admin::home.Choose Type')}}</label>
                                                <br>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="all" wire:model="is_custom" value="0" >
                                                    <label class="form-check-label" for="all">{{__('admin::home.Alls')}}</label> 
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="custom" wire:model="is_custom" value="1">
                                                    <label class="form-check-label" for="custom">{{__('admin::home.Custom')}}</label>
                                                </div> 
                                                @error("is_custom")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div> --}}


                                            <div class="col-xl-4">
                                                <label for="category-active" class="form-label text-black">{{__('admin::home.status')}}</label>
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
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">{{__('admin::home.Close')}}
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    {{__('admin::home.Save')}}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
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
