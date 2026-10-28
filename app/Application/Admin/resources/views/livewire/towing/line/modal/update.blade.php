<div>
    <div class="modal fade" id="updateTowingLine"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Update Towing Line</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
{{--                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)--}}
{{--                                                <div class="col-xl-6">--}}
{{--                                                    <label for="category-name" class="form-label text-black">Line Title {{$properties['native']}}</label>--}}
{{--                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"--}}
{{--                                                           id="category-name"--}}
{{--                                                           placeholder="Title" wire:model.defer="title.{{$localeCode}}">--}}
{{--                                                    @error("title." . $localeCode) <span--}}
{{--                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}

                                            <div class="col-xl-6">
                                                <label for="from_towing_city_id"
                                                       class="form-label text-black">From Towing City</label>
                                                <select class="form-control @error("from_towing_city_id") is-invalid @enderror" wire:model.defer="from_towing_city_id">
                                                    <option selected value="">--select Towing City--</option>
                                                    @foreach($towing_cities as $towing_city)
                                                        <option value="{{ $towing_city->id }}">{{ $towing_city->title }}</option>
                                                    @endforeach
                                                </select>

                                                @error("from_towing_city_id") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="to_towing_city_id"
                                                       class="form-label text-black">To Towing City</label>
                                                <select class="form-control @error("to_towing_city_id") is-invalid @enderror" wire:model.defer="to_towing_city_id">
                                                    <option selected value="">--select Towing City--</option>
                                                    @foreach($towing_cities as $towing_city)
                                                        <option value="{{ $towing_city->id }}">{{ $towing_city->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error("to_towing_city_id") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="type"
                                                       class="form-label text-black">Towing Type</label>
                                                <select class="form-control @error("towing_type_id") is-invalid @enderror" wire:model.defer="towing_type_id">
                                                    <option selected value="">--select towing type--</option>
                                                    @foreach($towing_types as $towing_type)
                                                        <option value="{{ $towing_type->id }}">{{ $towing_type->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error("towing_type_id") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="price"
                                                       class="form-label text-black">Price</label>
                                                <input class="form-control @error("price") is-invalid @enderror" wire:model.defer="price" type="number" min="0" step="0.05">
                                                @error("price") <span
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
