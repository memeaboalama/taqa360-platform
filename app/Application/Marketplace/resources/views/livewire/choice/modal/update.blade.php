<div>
    <div class="modal fade" id="updateChoice" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.update')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            <div class="col-xl-10">
                                                <label for="category-name" class="form-label text-black">@lang('marketplace::home.name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="category-name" placeholder="@lang('marketplace::home.name')" wire:model.defer="title.{{$localeCode}}">
                                                @error("title." . $localeCode) <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                            @endforeach


                                            <div class="col-xl-2">
                                                <label for="category-active" class="form-label text-black">@lang('marketplace::home.status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="category-active" class="label-success" wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @MerchantHasRole(['edit_menu choices'])
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">@lang('marketplace::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                @lang('marketplace::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    @endMerchantHasRole
                </form>
            </div>
        </div>
    </div>

</div>
