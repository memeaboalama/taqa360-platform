<div>
    <div class="modal fade" id="updateChoice" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('admin::home.update')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-5">
                                                    <label for="category-name" class="form-label text-black">@lang('admin::home.name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="category-name" placeholder="@lang('admin::home.name')" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                            <div class="col-xl-2">
                                                <label for="category-active" class="form-label text-black">@lang('admin::home.status')</label>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                        <span class="indicator-label" wire:loading.class="d-none">
                            @lang('admin::home.Save')
                        </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                            @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
