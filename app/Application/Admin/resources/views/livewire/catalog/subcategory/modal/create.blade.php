<div>
    <div class="modal fade" id="createSubcategory" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::catalog.create_new_subcategory')</h6>
                    <button type="button" class="btn-close" id="createSubcategoryCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="store"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                <div class="col-xl-6">
                                                    <label for="subcat-title-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::catalog.subcategory_title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-subcat-title-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('subcat-title-create-{{$localeCode}}','subcat-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="subcat-title-create-{{$localeCode}}"
                                                           placeholder="@lang('admin::catalog.title_placeholder')" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('subcat-title-create-{{$localeCode}}','subcat-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode)
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                <div class="col-12">
                                                    <label for="subcat-desc-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::catalog.description') {{$properties['native']}}
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-subcat-desc-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('subcat-desc-create-{{$localeCode}}','subcat-desc-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <textarea class="form-control @error("description." . $localeCode) is-invalid @enderror"
                                                              id="subcat-desc-create-{{$localeCode}}"
                                                              placeholder="@lang('admin::catalog.description_placeholder')" wire:model.defer="description.{{$localeCode}}"
                                                              onblur="translateLocalizedField('subcat-desc-create-{{$localeCode}}','subcat-desc-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)"
                                                              rows="3"></textarea>
                                                    @error("description." . $localeCode)
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            <div class="col-md-6">
                                                <label for="category" class="form-label text-black">@lang('admin::catalog.category') <span class="text-danger">*</span></label>
                                                <select class="form-control @error("category_id") is-invalid @enderror" wire:model.defer="category_id">
                                                    <option value="">@lang('admin::catalog.select_category')</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error("category_id")
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="slug" class="form-label text-black">@lang('admin::catalog.slug')</label>
                                                <input type="text" class="form-control @error("slug") is-invalid @enderror"
                                                       id="slug"
                                                       placeholder="@lang('admin::catalog.slug_placeholder_optional')" wire:model.defer="slug">
                                                @error("slug")
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-12">
                                                <label for="subcategory-active" class="form-label text-black">@lang('admin::home.status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="subcategory-active"
                                                           type="checkbox" {{ $is_active == '1' ? 'checked' : '' }}
                                                    >
                                                    <label for="subcategory-active" class="label-success"
                                                           wire:click="updateStatus('{{ $is_active == '1' ? '0' : '1' }}')"></label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="subcategory-image" class="form-label text-black">@lang('admin::catalog.subcategory_image') <span class="text-danger">*</span></label>
                                                <div class="d-flex align-items-center gap-4">
                                                    @if($imagePreview)
                                                        <div class="position-relative me-3">
                                                            <img src="{{ $imagePreview }}"
                                                                 alt="@lang('admin::catalog.image_preview')"
                                                                 class="rounded shadow-sm"
                                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                                    wire:click="removeImage"
                                                                    style="width: 24px; height: 24px; padding: 0; border-radius: 50%;">
                                                                ×
                                                            </button>
                                                        </div>
                                                    @endif

                                                    <div class="flex-grow-1">
                                                        <input type="file"
                                                               class="form-control @error('image') is-invalid @enderror"
                                                               id="subcategory-image"
                                                               wire:model="image"
                                                               accept="image/*">
                                                        @error('image')
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-text">
                                                            @lang('admin::catalog.image_requirements')
                                                        </div>

                                                        @if($image && $image->getClientOriginalName())
                                                            <div class="mt-2">
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                         style="width: {{ $image ? 100 : 0 }}%">
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted">
                                                                    @lang('admin::catalog.uploading'): {{ $image->getClientOriginalName() }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="createSubcategoryCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="store">@lang('admin::catalog.common.close')</button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store, image">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="store, image">
                                @lang('admin::catalog.common.save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store, image">
                                @lang('admin::catalog.common.wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
