<div>
    <div class="modal fade" id="createItem" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{ __('driver::home.Add New') }}</h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @error('category_id')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
                @enderror
                <form method="POST" wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            <div class="col-xl-6">
                                                <label for="category-name" class="form-label text-black">@lang('driver::home.name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="category-name" placeholder="{{  __('driver::home.name') }}" wire:model.defer="title.{{$localeCode}}">
                                                @error("title." . $localeCode) <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                            @endforeach

                                            <div class="row gy-3 mt-2">
                                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-6">
                                                    <label for="description" class="form-label text-black">@lang('driver::home.Description') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                    <textarea id="description" class="form-control text-black bg-gray-200" rows="7" placeholder="{{  __('driver::home.Description') }}" wire:model.defer="description.{{$localeCode}}"></textarea>
                                                    @error("description." . $localeCode)
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                @endforeach
                                            </div>


                                            <div class="col-xl-4">
                                                <label for="default_price" class="form-label text-black">@lang('driver::home.Default Price') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" min="0" step="0.05" id="default_price" placeholder="@lang('driver::home.Default Price')" wire:model.defer="default_price">
                                                @error("default_price")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-xl-4">
                                                <label for="calories" class="form-label text-black">@lang('driver::home.calories') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" min="0" id="calories" placeholder="{{ __("driver::home.calories") }}" wire:model.defer="calories">
                                                @error("calories")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>



                                            <div class="col-xl-4">
                                                <label for="preparing_time" class="form-label text-black">@lang('driver::home.preparing_time') <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" min="0" id="preparing_time" placeholder="{{ __("driver::home.preparing_time") }}" wire:model.defer="preparing_time">
                                                @error("preparing_time")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- image --}}

                                            <div class="col-xl-10">
                                                <label for="image" class="form-label text-black">@lang('driver::home.image') <span class="text-danger">*</span></label>
                                                <label for="image" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                                                <input type="file" class="form-control" id="image" placeholder="{{ __("driver::home.image") }}" wire:model.defer="image">
                                                @error("image")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="addition_group_ids" class="form-label text-black">@lang('driver::home.additions_groups')</label>
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


                                            <div class="col-xl-2">
                                                <label for="category-active" class="form-label text-black">@lang('driver::home.status')</label>
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
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('driver::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('driver::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>