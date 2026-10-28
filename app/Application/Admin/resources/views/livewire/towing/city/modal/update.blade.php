<div>
    <div class="modal fade" id="updateTowingCity"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Update Towing City</h6>
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
                                                    <label for="category-name" class="form-label text-black">Towing City Title {{$properties['native']}}</label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="category-name"
                                                           placeholder="Title" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach

                                            <div class="col-xl-4">
                                                <label for="priority"
                                                       class="form-label text-black">Priority</label>
                                                <input
                                                    id="priority"
                                                    type="number"
                                                    min="1"
                                                    step="1"
                                                    class="form-control @error("priority") is-invalid @enderror"
                                                    wire:model.defer="priority">

                                                @error("priority") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                                <div class="col-xl-4">
                                                    <label for="pickup-active"
                                                           class="form-label text-black">Can Pickup</label>
                                                    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                        <input id="pickup-active"
                                                               type="checkbox" {{ $can_pickup == 'active' ? 'checked' : '' }}>
                                                        <label for="category-active" class="label-success"
                                                               wire:click="updatePickup('{{ $can_pickup == 'active' ? 'inactive' : 'active' }}')"></label>
                                                    </div>
                                                </div>

                                                <div class="col-xl-4">
                                                    <label for="dropoff-active"
                                                           class="form-label text-black">Can Dropoff</label>
                                                    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                        <input id="dropoff-active"
                                                               type="checkbox" {{ $can_dropoff == 'active' ? 'checked' : '' }}>
                                                        <label for="category-active" class="label-success"
                                                               wire:click="updateDropoff('{{ $can_dropoff == 'active' ? 'inactive' : 'active' }}')"></label>
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
