<div>
    <div class="modal fade" id="showItem" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{ __('marketplace::home.show item') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="col-xxl-12 col-xl-12">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-body p-0">
                                    <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                                        <div>
                                            <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="showItemTabs" role="tablist" wire:ignore>

                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="showBasicInfo-tab" data-bs-toggle="tab"
                                                        data-bs-target="#showBasicInfo-tab-pane" type="button" role="tab"
                                                        aria-controls="showBasicInfo-tab-pane" aria-selected="true"><i
                                                            class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.Item Basic Info') }}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="showItemImages-tab" data-bs-toggle="tab"
                                                        data-bs-target="#showItemImages-tab-pane" type="button" role="tab" tabindex="-1"
                                                        aria-controls="showItemImages-tab-pane" aria-selected="false"><i
                                                            class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.Item Images') }}</button>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="tab-content" id="showItemTabsContent">
                            <div class="tab-pane active show fade p-0 border-0" id="showBasicInfo-tab-pane"
                                role="tabpanel" aria-labelledby="showBasicInfo-tab" tabindex="0" wire:ignore.self>
                                <div class="row gx-5">
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                        <div class="card custom-card shadow-none mb-0">
                                            <div class="card-body p-0">
                                                <div class="row gy-3">
                                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                    <div class="col-xl-6">
                                                        <label class="form-label text-black">@lang('marketplace::home.name') {{$properties['native']}}</label>
                                                        <input type="text" class="form-control" value="{{ $title[$localeCode] ?? '' }}" disabled />
                                                    </div>
                                                    @endforeach

                                                    <div class="row gy-3 mt-2">
                                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                        <div class="col-xl-6">
                                                            <label class="form-label text-black">@lang('marketplace::home.Description') {{$properties['native']}}</label>
                                                            <textarea class="form-control text-black bg-gray-200" rows="7" disabled>{{ $description[$localeCode] ?? '' }}</textarea>
                                                        </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="col-xl-4">
                                                        <label class="form-label text-black">@lang('marketplace::home.Before Price')</label>
                                                        <input type="text" class="form-control" value="{{ $default_price }}" disabled />
                                                    </div>

                                                    <div class="col-xl-4">
                                                        <label class="form-label text-black">@lang('marketplace::home.calories')</label>
                                                        <input type="text" class="form-control" value="{{ $calories }}" disabled />
                                                    </div>

                                                    <div class="col-xl-4">
                                                        <label class="form-label text-black">@lang('marketplace::home.preparing_time')</label>
                                                        <input type="text" class="form-control" value="{{ $preparing_time }}" disabled />
                                                    </div>

                                                    <div class="col-xl-4">
                                                        <label class="form-label text-black">@lang('marketplace::home.item status')</label>
                                                        <div>
                                                            <h4 class="badge {{ \App\Support\Enum\ActiveEnum::from($status)->color() }}">{{ \App\Support\Enum\ActiveEnum::from($status)->trans() }}</h4>
                                                        </div>
                                                    </div>

                                                    <div class="row gy-3 mt-2">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <table class="table">
                                                                    <thead>
                                                                        <th>@lang('admin::home.servicer type') </th>
                                                                        <th>@lang('admin::home.Default Price')</th>
                                                                        <th>@lang('admin::home.Price Status')</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($item_prices as $index => $item_price)
                                                                        <tr>
                                                                            <td>
                                                                                <input value="{{ \Illuminate\Support\Facades\Lang::has('admin::home.'.$item_prices[$index]['type_service_name']) ? __('admin::home.'.$item_prices[$index]['type_service_name']) : $item_prices[$index]['type_service_name'] }}" class="form-control" disabled />
                                                                            </td>
                                                                            <td>
                                                                                <input value="{{ $item_prices[$index]['price'] }}" class="form-control" disabled />
                                                                            </td>
                                                                            <td>
                                                                                <h4 class="badge {{ $item_prices[$index]['status'] == 'active' ? 'bg-success' : 'bg-danger' }}">{{ $item_prices[$index]['status'] == 'active' ? __('marketplace::home.Active') : __('marketplace::home.Inactive') }}</h4>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($image_path)
                                                    <div class="col-xl-10">
                                                        <label class="form-label text-black">@lang('marketplace::home.image')</label>
                                                        <div>
                                                            <img src="{{ $image_path }}" class="img-thumbnail" width="150" height="150">
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if($addition_groups && count($addition_groups) > 0)
                                                    <div class="col-xl-6">
                                                        <label class="form-label text-black">@lang('marketplace::home.additions_groups')</label>
                                                        <br>
                                                        @foreach ($addition_groups as $addtion_group)
                                                        <label class="form-check-label mt-2" style="cursor: pointer">
                                                            {{ $addtion_group->title }}
                                                            [
                                                            @foreach (\Domain\MarketPlace\Models\Choice::whereIn('id',$addtion_group->choices()->pluck('choice_id')->toArray())->get() as $addtion )
                                                            {{ $addtion->title }}
                                                            @if (!$loop->last),@endif
                                                            @endforeach
                                                            ]
                                                        </label>
                                                        <br>
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane inactive show fade p-0 border-0" id="showItemImages-tab-pane"
                                role="tabpanel" aria-labelledby="showItemImages-tab-pane" tabindex='2' wire:ignore.self>

                                <div class="row gx-5">
                                    <div class="col-xl-10">
                                        @if($existing_images && count($existing_images) > 0)
                                        <div class="mt-3">
                                            <div class="row">
                                                @foreach ($existing_images as $index => $image)
                                                <div class="col-md-3 mb-3">
                                                    <img src="{{ $image['url'] }}" class="img-thumbnail" width="150" height="150">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @else
                                        <p class="text-muted mt-3">@lang('marketplace::home.no_images')</p>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('marketplace::home.Close')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
