<div>
    <div class="modal fade" id="createItem" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{ __('marketplace::home.Add New') }}</h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>
                @error('category_id')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
                @enderror
                <form method="POST" wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card custom-card">
                                    <div class="card-body p-0">
                                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                                            <div>
                                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist" wire:ignore>

                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="basicItemInfo-tab" data-bs-toggle="tab"
                                                            data-bs-target="#basicItemInfo-tab-pane" type="button" role="tab"
                                                            aria-controls="basicItemInfo-tab-pane" aria-selected="true"><i
                                                                class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.Item Basic Info') }}</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="itemImages-tab" data-bs-toggle="tab"
                                                            data-bs-target="#itemImages-tab-pane" type="button" role="tab" tabindex="-1"
                                                            aria-controls="itemImages-tab-pane" aria-selected="false"><i
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
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane active show fade p-0 border-0" id="basicItemInfo-tab-pane"
                                    role="tabpanel" aria-labelledby="basicItemInfo-tab" tabindex="0" wire:ignore.self>
                                    <div class="row gx-5">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                            <div class="card custom-card shadow-none mb-0">
                                                <div class="card-body p-0">
                                                    <div class="row gy-3">
                                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                        <div class="col-xl-6">
                                                            <label for="category-name" class="form-label text-black">@lang('marketplace::home.name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="category-name" placeholder="{{  __('marketplace::home.name') }}" wire:model.defer="title.{{$localeCode}}">
                                                            @error("title." . $localeCode) <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                        </div>
                                                        @endforeach

                                                        <div class="row gy-3 mt-2">
                                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                            <div class="col-xl-6">
                                                                <label for="description" class="form-label text-black">@lang('marketplace::home.Description') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                                <textarea id="description" class="form-control text-black bg-gray-200" rows="7" placeholder="{{  __('marketplace::home.Description') }}" wire:model.defer="description.{{$localeCode}}"></textarea>
                                                                @error("description." . $localeCode)
                                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            @endforeach
                                                        </div>


                                                        <div class="col-xl-4">
                                                            <label for="default_price" class="form-label text-black">@lang('marketplace::home.Before Price') <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" min="0" step="0.05" id="default_price" placeholder="@lang('marketplace::home.Before Price')" wire:model.defer="default_price">
                                                            @error("default_price")
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>


                                                        <div class="col-xl-4">
                                                            <label for="calories" class="form-label text-black">@lang('marketplace::home.calories') <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" min="0" id="calories" placeholder="{{ __("marketplace::home.calories") }}" wire:model.defer="calories">
                                                            @error("calories")
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>



                                                        <div class="col-xl-4">
                                                            <label for="preparing_time" class="form-label text-black">@lang('marketplace::home.preparing_time') <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" min="0" id="preparing_time" placeholder="{{ __("marketplace::home.preparing_time") }}" wire:model.defer="preparing_time">
                                                            @error("preparing_time")
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <label for="category-active" class="form-label text-black">@lang('marketplace::home.item status')</label>
                                                            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                                <input id="category-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                                <label for="category-active" class="label-success" wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
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
                                                                            @foreach($item_prices as $index =>$item_price)
                                                                            <tr>
                                                                                <input wire:model.defer="item_prices.{{$index}}.type_service_id" class="form-control" type="hidden" />
                                                                                <td>
                                                                                    <input value="{{ \Illuminate\Support\Facades\Lang::has('admin::home.'.$item_prices[$index]['type_service_name']) ? __('admin::home.'.$item_prices[$index]['type_service_name']) : $item_prices[$index]['type_service_name'] }}" class="form-control" placeholder="@lang('admin::home.servicer type')" disabled />
                                                                                </td>

                                                                                <td>
                                                                                    <input wire:model.defer="item_prices.{{$index}}.price" class="form-control" />
                                                                                <td>
                                                                                    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                                                        <input id="{{'active'.$index}}"
                                                                                            type="checkbox" {{ $item_prices[$index]['status'] == "active"  ? 'checked' : '' }}>
                                                                                        <label for="{{'active'.$index}}" class="label-success"
                                                                                            wire:click="updateStatusRow('{{ $index }}')" wire:ignore.self></label>
                                                                                    </div>
                                                                                </td>


                                                                            </tr>

                                                                            @endforeach


                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- image --}}

                                                        <div class="col-xl-10">
                                                            <label for="image" class="form-label text-black">@lang('marketplace::home.image') <span class="text-danger">*</span></label>
                                                            <label for="image" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                                                            <input type="file" class="form-control" id="image" placeholder="{{ __("marketplace::home.image") }}" wire:model.defer="image">
                                                            @error("image")
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-xl-6">
                                                            <label for="addition_group_ids" class="form-label text-black">@lang('marketplace::home.additions_groups')</label>
                                                            <br>
                                                            @foreach ($addition_groups as $addtion_group)
                                                            <input class="form-check-input mt-2" id="addition_group_ids{{ $addtion_group->id }}" type="checkbox" wire:model="addition_group_ids" value="{{ $addtion_group->id }}">
                                                            <label class="form-check-label mt-2" for="addition_group_ids{{ $addtion_group->id }}" style="cursor: pointer">
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

                                                            @error("addition_group_ids")
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane inactive show fade p-0 border-0" id="itemImages-tab-pane"
                                    role="tabpanel" aria-labelledby="itemImages-tab-pane" tabindex='2' wire:ignore.self>

                                    <div class="row gx-5">
                                        <div class="col-xl-10">

                                            <input type="file"
                                                class="form-control"
                                                name="additional_images"
                                                id="additional_images"
                                                multiple
                                                wire:model="additional_images">

                                            @error('additional_images.*')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror

                                            @if ($additional_images)
                                            <div class="mt-3">
                                                <div class="row">
                                                    @foreach ($additional_images as $index => $image)
                                                    <div class="col-md-3 mb-3 position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" width="100" height="100">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 start-0"
                                                            wire:click="removeAdditionalImage({{ $index }})">
                                                            ×
                                                        </button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
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
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>