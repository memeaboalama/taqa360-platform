<div>
    <div class="modal fade" id="updateItem" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('admin::home.update item')</h6>
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
                                                <div class="col-xl-6">
                                                    <label for="category-name" class="form-label text-black">@lang('admin::home.name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="category-name" placeholder="@lang('admin::home.name')" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach

                                            <div class="row gy-3 mt-2">
                                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                    <div class="col-xl-6">
                                                        <label for="description" class="form-label text-black">@lang('admin::home.Description') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                        <textarea id="description" class="form-control text-black bg-gray-200" rows="7" placeholder="@lang('admin::home.Description')" wire:model.defer="description.{{$localeCode}}"></textarea>
                                                        @error("description." . $localeCode)
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                            </div>


                                            <div class="col-xl-4" >
                                                <label for="default_price" class="form-label text-black">@lang('admin::home.Before Price') <span class="text-danger">*</span></label>
                                                <input value="0" type="number" class="form-control" min="0" step="0.05" id="default_price" placeholder="Before Price" wire:model.defer="default_price">
                                                @error("default_price")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-xl-4">
                                                <label for="calories" class="form-label text-black">@lang('admin::home.calories') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" min="0" id="calories" placeholder="{{ __("admin::home.calories") }}" wire:model.defer="calories">
                                                @error("calories")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>



                                            <div class="col-xl-4">
                                                <label for="preparing_time" class="form-label text-black">@lang('admin::home.preparing_time') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" min="0" id="preparing_time" placeholder="{{ __("admin::home.preparing_time") }}" wire:model.defer="preparing_time">
                                                @error("preparing_time")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="row gy-3 mt-2">
                                            <div class="col-md-12">
                                            <div class="form-group">

<table class="table">
    <thead>
    <th>@lang('admin::home.servicer type') </th>
    <th>@lang('admin::home.Default Price')</th>
    <th>@lang('admin::home.status')</th>

    </thead>

    <tbody>
    @foreach($item_prices as $index =>$item_price)
        <tr>
            <input wire:model.defer="item_prices.{{$index}}.type_service_id" class="form-control"  type="hidden" />
            <td>
            <input value="@lang('admin::home.'.($item_prices[$index]['type_service_name']))"  class="form-control" disabled />
                            </td>

                            <td>
                            <input wire:model.defer="item_prices.{{$index}}.price" class="form-control"  />
            <td>
            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
            <input id="{{'active'.$index}}" 
                       type="checkbox" {{ $item_prices[$index]['status'] == "active"  ? 'checked' : '' }}>
                <label for="{{'active'.$index}}" class="label-success"
                 wire:click="updateStatusRow('{{ $index }}')"  wire:ignore.self></label>
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
                                                <label for="image" class="form-label text-black">@lang('admin::home.image') <span class="text-danger">*</span></label>
                                                <label for="image" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                                                <input type="file" class="form-control" id="image" placeholder="{{ __("admin::home.image") }}" wire:model.defer="image">
                                                @error("image")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-xl-2">
                                                <img src="{{ $image_path }}"
                                                     class="img-fluid rounded-start" alt="item image" width="100" height="100">
                                            </div>


                                            <div class="col-xl-6">
                                                <label for="addition_group_ids" class="form-label text-black">@lang('admin::home.additions_groups')</label>
                                                <br>
                                                @foreach ($addition_groups as $addtion_group)
                                                    <input class="form-check-input mt-2" id="addition_group_ids_edit{{ $addtion_group->id }}" type="checkbox" wire:model="addition_group_ids" value="{{ $addtion_group->id }}">
                                                    <label class="form-check-label mt-2" for="addition_group_ids_edit{{ $addtion_group->id }}" style="cursor: pointer">
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
