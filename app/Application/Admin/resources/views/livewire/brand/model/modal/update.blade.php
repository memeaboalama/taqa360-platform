<div>
    <div class="modal fade" id="updateModel"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Update Model</h6>
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
                                                    <label for="category-name" class="form-label text-black">Model Title {{$properties['native']}}</label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="category-name"
                                                           placeholder="Title" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                                <div class="col-xl-6">
                                                    <label for="type"
                                                           class="form-label text-black">Brand</label>
                                                    <select class="form-control @error("brand_id") is-invalid @enderror" wire:model.defer="brand_id">
                                                        <option selected value="">--select brand--</option>
                                                        @foreach($brands as $brand)
                                                            <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error("brand_id") <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
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
