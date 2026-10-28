<div class="tab-pane show active text-muted" id="home1-justified" role="tabpanel" wire:ignore.self>
    <div class="row gx-5">
        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
            <div class="card custom-card shadow-none mb-0">
                <div class="card-body p-0">
                    <form method="POST" wire:submit.prevent="updateService">
                        <div class="row gy-3">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <div class="col-xl-6">
                                <label for="service-name" class="form-label text-black">Service Name {{$properties['native']}}</label>
                                <input type="text" class="form-control @error(" title." . $localeCode) is-invalid @enderror" id="service-name" placeholder="Name" wire:model.defer="title.{{$localeCode}}">
                                @error("title." . $localeCode)
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            @endforeach
                        </div>
                        <div class="row gy-3 mt-2">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <div class="col-xl-6">
                                <label for="description" class="form-label text-black">Description {{$properties['native']}}</label>
                                <textarea id="description" class="form-control @error(" description." . $localeCode) is-invalid @enderror text-black bg-gray-200" rows="7" placeholder="Enter helpful Description ......." wire:model.defer="description.{{$localeCode}}"></textarea>
                                @error("description." . $localeCode)
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            @endforeach
                        </div>

                        <div class="row gy-3 mt-2">


                            <div class="col-xl-2">
                                <label for="default_price" class="form-label text-black">Default Price</label>
                                <input type="number" class="form-control @error('default_price') is-invalid @enderror" min="0" step="0.05" id="default_price" placeholder="Default Price" wire:model.defer="default_price">
                                @error("default_price")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xl-2">
                                <label for="service-active" class="form-label text-black">Active</label>
                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                    <input id="service-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                    <label for="service-active" class="label-success" wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                </div>
                            </div>

                 
                            <div class="col-xl-4">
                                <label for="merchant_category_ids" class="form-label text-black">@lang('marketplace::home.Category Name')</label>
                                <br>
                                @foreach ($categories as $key => $value)
                                <input class="form-check-input" type="checkbox" wire:model="merchant_category_ids" value="{{ $key }}">
                                <label class="form-check-label">
                                    {{ $value }}
                                </label>
                                @endforeach

                                @error("merchant_category_ids")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="row gy-3 mt-3">
                            <div class="col-xl-4 d-grid mt-4">
                                <button class="btn btn-lg btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
