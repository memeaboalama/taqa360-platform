<div>
    <div class="modal fade" id="updateMerchantCategory"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Update Merchant Category</h6>
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
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-6">
                                                    <label for="category-name" class="form-label text-black">Merchant Category Title {{$properties['native']}}</label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="category-name"
                                                           placeholder="Title" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                            <div class="col-xl-5">
                                                <label for="merchant_id"
                                                       class="form-label text-black">Merchants</label>
                                                <select class="form-control @error("merchant_id") is-invalid @enderror" wire:model.defer="merchant_id" id="merchant_id">
                                                    <option selected value="">--select merchant--</option>
                                                    @foreach($merchants as $merchant)
                                                        <option value="{{ $merchant->id }}">{{ $merchant->company_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("merchant_id") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="category-priority"
                                                       class="form-label text-black">@lang('admin::lang.Priority') </label>
                                                <select
                                                    wire:model.defer="priority"
                                                    id="category-priority"
                                                    class="form-control @error("priority") is-invalid @enderror">
                                                    <option selected value="">---@lang('admin::lang.Select') @lang('admin::lang.Priority')---</option>
                                                    <option value="low">@lang('admin::lang.Low')</option>
                                                    <option value="medium">@lang('admin::lang.Medium')</option>
                                                    <option value="high">@lang('admin::lang.High')</option>
                                                </select>
                                                @error('priority') <span
                                                    class="text-danger d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <label for="category-active"
                                                       class="form-label text-black">Status</label>
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
                                data-bs-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    Save
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                    Wait <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
