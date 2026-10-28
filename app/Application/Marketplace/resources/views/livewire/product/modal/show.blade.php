<div>
    <div class="modal fade" id="showProduct" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="showProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 720px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="showProductLabel">{{ __('admin::main.actions.view') }} {{ __('admin::main.lang.product') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card custom-card">
                                    <div class="card-body p-0">
                                        <div class="p-3 border-bottom border-block-end-dashed">
                                            <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="showTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="showBasicInfo-tab" data-bs-toggle="tab"
                                                        data-bs-target="#showBasicInfo-tab-pane" type="button" role="tab"
                                                        aria-controls="showBasicInfo-tab-pane" aria-selected="true">
                                                        <i class="ri-bill-line me-1 align-middle d-inline-block"></i>
                                                        {{ __('admin::main.home.Item Basic Info') }}
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="showImages-tab" data-bs-toggle="tab"
                                                        data-bs-target="#showImages-tab-pane" type="button" role="tab" tabindex="-1"
                                                        aria-controls="showImages-tab-pane" aria-selected="false">
                                                        <i class="ri-image-line me-1 align-middle d-inline-block"></i>
                                                        {{ __('admin::main.home.Item Images') }}
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="showPrices-tab" data-bs-toggle="tab"
                                                        data-bs-target="#showPrices-tab-pane" type="button" role="tab" tabindex="-1"
                                                        aria-controls="showPrices-tab-pane" aria-selected="false">
                                                        <i class="ri-price-tag-3-line me-1 align-middle d-inline-block"></i>
                                                        {{ __('admin::main.home.Item Prices') }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="tab-content" id="showTabContent">
                                <div class="tab-pane active show fade p-0 border-0" id="showBasicInfo-tab-pane"
                                    role="tabpanel" aria-labelledby="showBasicInfo-tab" tabindex="0">
                                    <div class="row gy-3">
                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <div class="{{ $localeCode === 'ar' ? 'col-xl-6' : 'col-xl-5' }}">
                                            <label class="form-label text-black">
                                                {{ __('admin::main.fields.title') }} {{ $properties['native'] }}
                                            </label>
                                            <input type="text" class="form-control" value="{{ $title[$localeCode] ?? '' }}" disabled>
                                        </div>
                                        @endforeach

                                        <div class="col-xl-1">
                                            <label class="form-label text-black">{{ __('admin::main.fields.status') }}</label>
                                            <div class="mt-2">
                                                @if($status == 'active')
                                                <span class="badge bg-success">{{ __('admin::main.status.active') }}</span>
                                                @else
                                                <span class="badge bg-danger">{{ __('admin::main.status.inactive') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <div class="col-xl-6">
                                            <label class="form-label text-black">
                                                {{ __('admin::main.fields.description') }} {{ $properties['native'] }}
                                            </label>
                                            <textarea class="form-control" rows="3" disabled>{{ $description[$localeCode] ?? '' }}</textarea>
                                        </div>
                                        @endforeach

                                        <div class="col-md-4">
                                            <label class="form-label text-black">{{ __('admin::main.fields.category') }}</label>
                                            <input type="text" class="form-control" value="{{ $main_category }}" disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label text-black">{{ __('admin::main.fields.subcategory') }}</label>
                                            <input type="text" class="form-control" value="{{ $sub_category }}" disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label text-black">{{ __('admin::main.fields.child_category') }}</label>
                                            <input type="text" class="form-control" value="{{ $child_category }}" disabled>
                                        </div>

                                        <div class="col-xl-6">
                                            <label class="form-label text-black">{{ __('admin::main.fields.default_price') }}</label>
                                            <input type="text" class="form-control" value="{{ number_format($default_price, 2) }} {{ config('app.currency') }}" disabled>
                                        </div>

                                        <!-- <div class="col-xl-4">
                                            <label class="form-label text-black">{{ __('admin::main.fields.calories') }}</label>
                                            <input type="text" class="form-control" value="{{ $calories }}" disabled>
                                        </div>

                                        <div class="col-xl-4">
                                            <label class="form-label text-black">{{ __('admin::main.fields.preparing_time') }}</label>
                                            <input type="text" class="form-control" value="{{ $preparing_time }}" disabled>
                                        </div> -->

                                        <div class="col-xl-12">
                                            <label class="form-label text-black">{{ __('admin::main.fields.image') }}</label>
                                            <div class="mt-2 d-flex justify-content-center">
                                                @if($image_path)
                                                <img src="{{ $image_path }}" class="img-thumbnail" width="200" alt="image">
                                                @else
                                                <span class="text-muted">{{ __('marketplace::home.no_images') }}</span>
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="showImages-tab-pane"
                                    role="tabpanel" aria-labelledby="showImages-tab" tabindex="1">
                                    <div class="row gy-3">
                                        @forelse($existing_files as $file)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ $file['url'] }}" class="card-img-top"
                                                    alt="{{ $file['original_name'] }}" style="height: 150px; object-fit: cover;">
                                                <div class="card-body text-center p-2">
                                                    <small class="text-muted d-block">{{ \Illuminate\Support\Str::limit($file['original_name'], 20) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12">
                                            <div class="text-center text-muted py-4">
                                                {{ __('marketplace::home.no_images') }}
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="showPrices-tab-pane"
                                    role="tabpanel" aria-labelledby="showPrices-tab" tabindex="2">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('admin::main.home.servicer type') }}</th>
                                                    <th>{{ __('admin::main.home.Default Price') }}</th>
                                                    <th>{{ __('admin::main.home.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item_prices as $item_price)
                                                <tr>
                                                    <td>{{ $item_price['type_service_name'] }}</td>
                                                    <td>{{ $item_price['price'] !== null ? number_format($item_price['price'], 2) . ' ' . config('app.currency') : '-' }}</td>
                                                    <td>
                                                        @if($item_price['status'] == 'active')
                                                        <span class="badge bg-success">{{ __('admin::main.status.active') }}</span>
                                                        @else
                                                        <span class="badge bg-danger">{{ __('admin::main.status.inactive') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        {{ __('admin::main.actions.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>