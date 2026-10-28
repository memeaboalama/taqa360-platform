<div>
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/filepond/filepond.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/filepond-plugin-image-edit/filepond-plugin-image-edit.min.css')}}">

    <div class="modal fade" id="createItem" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{ __('marketplace::home.Add New') }}</h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                                <input wire:model.defer="item_prices.{{$index}}.type_service_id" class="form-control" type="hidden" />
                                                                                <td>
                                                                                    <input value="@lang('admin::home.'.($item_prices[$index]['type_service_name']))" class="form-control" disabled />
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


                                                        <div class="col-xl-2">
                                                            <label for="category-active" class="form-label text-black">@lang('marketplace::home.status')</label>
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
                                <div class="tab-pane inactive show fade p-0 border-0" id="itemImages-tab-pane"
                                    role="tabpanel" aria-labelledby="itemImages-tab-pane" tabindex='2' wire:ignore.self>

                                    <div class="row gx-5">
                                        <div class="col-xl-10">

                                            <input type="file" class="multiple-filepond" name="att" id="att" multiple data-allow-reorder='true' data-max-files="10">
                                            <!-- Filepond JS -->
                                            <script src="{{asset('assets/vendor/libs/filepond/filepond.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-image-edit/filepond-plugin-image-edit.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js')}}"></script>
                                            <script src="{{asset('assets/vendor/libs/filepond-plugin-image-transform/filepond-plugin-image-transform.min.js')}}"></script>
                                            <script>
                                                /* filepond */
                                                FilePond.registerPlugin(
                                                    FilePondPluginImagePreview,
                                                    FilePondPluginImageExifOrientation,
                                                    FilePondPluginFileValidateSize,
                                                    FilePondPluginFileEncode,
                                                    FilePondPluginImageEdit,
                                                    FilePondPluginFileValidateType,
                                                    //FilePondPluginImageCrop,
                                                    //FilePondPluginImageResize,
                                                    FilePondPluginImageTransform
                                                );

                                                /* File upload */
                                                FilePond.create(
                                                    document.querySelector('#att'), {
                                                        labelIdle: `سحب وافلات الملف او <span class="filepond--label-action" style='color: blue;'>استعراض</span>`,
                                                        acceptedFileTypes: ['image/*'],
                                                        allowMultiple: false,
                                                        allowFileTypeValidation: true,
                                                        labelFileTypeNotAllowed: 'نوع الملف غير مقبول ',
                                                        fileValidateTypeLabelExpectedTypes: 'الملفات المقبولة هي الصور فقط ',
                                                        allowFileSizeValidation: true,
                                                        fileSizeBase: 4000000,
                                                        maxFileSize: 4000000,
                                                        labelMaxFileSizeExceeded: 'حجم الملف غير مقبول',
                                                        labelMaxFileSize: 'اقصى حجم (4MB)',
                                                    }
                                                );
                                            </script>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('marketplace::home.Close')
                            </button>
                            <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('marketplace::home.Save')
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>

                        </div>
                </form>
            </div>
        </div>
    </div>

</div>