<div>
    <div class="modal fade" id="createPackage"  wire:ignore.self
         data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Create New Package')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">

                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                <div class="{{ $localeCode == 'ar' ? 'col-xl-6' : 'col-xl-5' }}">
                                                    <label for="package-title-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::lang.Package Title') ({{$properties['native']}}) <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-package-title-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('package-title-create-{{$localeCode}}','package-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="package-title-create-{{$localeCode}}"
                                                           placeholder="@lang('admin::lang.Title')" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('package-title-create-{{$localeCode}}','package-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach

                                                <div class="col-xl-1">
                                                    <label for="category-active"
                                                           class="form-label text-black">@lang('admin::lang.Status')</label>
                                                    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                        <input id="category-active"
                                                               type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                        <label for="category-active" class="label-success"
                                                               wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                    </div>
                                                </div>


                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                <div class="col-xl-6">
                                                    <label for="package-desc-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::lang.Package Description') ({{$properties['native']}}) <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-package-desc-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('package-desc-create-{{$localeCode}}','package-desc-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <textarea class="form-control @error("description." . $localeCode) is-invalid @enderror" id="package-desc-create-{{$localeCode}}" rows="5" placeholder="@lang('admin::lang.Package Description') ({{$properties['native']}})" wire:model.defer="description.{{$localeCode}}" onblur="translateLocalizedField('package-desc-create-{{$localeCode}}','package-desc-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)"></textarea>
                                                    @error("description." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="row gy-3 mt-2">
                                            <div class="col-md-12">
                                            <div class="form-group">

<table class="table">
    <thead>
    <th>@lang('admin::home.servicer type') </th>
    <th>@lang('admin::home.commission percentage') <span class="text-danger">*</span></th>
    <!-- <th>@lang('admin::home.discount percentage') <span class="text-danger">*</span></th> -->
    <th>@lang('admin::home.Description')</th>
    <th>@lang('admin::home.status')</th>

    </thead>

    <tbody>
    @foreach($package_type_services as $index =>$package_type_service)
        <tr>
            <input wire:model.defer="package_type_services.{{$index}}.type_service_id" class="form-control"  type="hidden" />
            <td>
            <input value="@lang('admin::home.'.$package_type_services[$index]['type_service_name'])" class="form-control" disabled />
                            </td>

            <td>
                <input type="number" min="0" step="0.005" required class="form-control" wire:model.defer="package_type_services.{{$index}}.commission">
            </td>

            <!-- <td>
                <input type="number" min="0" required class="form-control"  wire:model.defer="package_type_services.{{$index}}.discount_rate">
            </td> -->
            <td>
            <input wire:model.defer="package_type_services.{{$index}}.desc"  class="form-control" />
                            </td>
            <td>
            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
            <input id="{{'active'.$index}}"
                       type="checkbox" {{ $package_type_services[$index]['status'] == "active"  ? 'checked' : '' }}>
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
                                        <div class="row gy-3 mt-2">
                                            <div class="col-md-6">
                                                @error("terms_ar")
                                                <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
                                                @enderror
                                                <livewire:admin::package.modal.create.ar.quill :create_value_ar="$terms_ar">
                                            </div>
                                            <div class="col-md-6">
                                                @error("terms_en")
                                                <span class="text-danger d-block mt-2 mb-2">{{ $message }}</span>
                                                @enderror
                                                <livewire:admin::package.modal.create.en.quill :create_value_en="$terms_en">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">@lang('admin::lang.Close')
                        </button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label">
                                    @lang('admin::lang.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
