<div>
    <div class="modal fade" id="createProviderCategory"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Create New Provider Category</h6>
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
                                                    <label for="category-name" class="form-label text-black">Provider Category Title {{$properties['native']}}</label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="category-name"
                                                           placeholder="Title" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                                <div class="col-xl-5">
                                                    <label for="main_category_id"
                                                           class="form-label text-black">Main Category</label>
                                                    <select class="form-control @error("main_category_id") is-invalid @enderror" wire:model.defer="main_category_id" id="main_category_id">
                                                        <option selected value="">--select main category--</option>
                                                        @foreach($main_categories as $main_category)
                                                            <option value="{{ $main_category->id }}">{{ $main_category->title }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error("main_category_id") <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                                </div>

                                                <div class="col-xl-5">
                                                    <label for="selection"
                                                           class="form-label text-black">Selection</label>
                                                    <select class="form-control @error("selection") is-invalid @enderror" wire:model.defer="selection" id="selection">
                                                        <option selected value="">--select--</option>
                                                        <option value="single">Single</option>
                                                        <option value="multi">Multi</option>
                                                    </select>

                                                    @error("selection") <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror

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
