@extends('admin::layout.layout')

@section('title', __('admin::lang.Manage Content') . ' - ' . $placement->getTranslation('name', app()->getLocale()))

@section('content')
    <div class="main-container container-fluid">
        <div class="inner-body">
            <div class="page-header">
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin:home') }}">{{ __('admin::lang.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin:ads-content.index') }}">{{ __('admin::lang.Ads Content') }}</a></li>
                        <li class="breadcrumb-item active">{{ $placement->getTranslation('name', app()->getLocale()) }}</li>
                    </ol>
                </div>
            </div>

            <div class="row row-sm mb-4">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">{{ __('admin::lang.Type') }}</small>
                                    <h6>{{ $placement->type->label() }}</h6>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">{{ __('admin::lang.Location') }}</small>
                                    <h6>{{ $placement->location }}</h6>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">{{ __('admin::lang.Desktop Size') }}</small>
                                    <h6>{{ $placement->settings['display']['devices']['desktop']['width'] ?? 'N/A' }}x{{ $placement->settings['display']['devices']['desktop']['height'] ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ __('admin::lang.Tablet') }}: {{ $placement->settings['display']['devices']['tablet']['width'] ?? 'N/A' }}x{{ $placement->settings['display']['devices']['tablet']['height'] ?? 'N/A' }}</small>
                                    <br>
                                    <small class="text-muted">{{ __('admin::lang.Mobile') }}: {{ $placement->settings['display']['devices']['mobile']['width'] ?? 'N/A' }}x{{ $placement->settings['display']['devices']['mobile']['height'] ?? 'N/A' }}</small>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">{{ __('admin::lang.Max Items') }}</small>
                                    <h6>{{ $placement->settings['slider_config']['max_items'] ?? 'Unlimited' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                     @if($placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER)
                        <livewire:admin::ads-content.header-settings :placementId="$placement->id" />
                     @elseif($placement->type === \App\Support\Enum\PlacementTypeEnum::FOOTER)
                        <livewire:admin::ads-content.footer-settings :placementId="$placement->id" />
                    @else
                        <div class="card custom-card">
                            <div class="card-header border-bottom-0 pb-0">
                                <ul class="nav nav-tabs nav-tabs-header mb-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#active-content" role="tab">
                                            <i class="fe fe-check-circle me-1"></i> {{ __('admin::lang.active_content') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#history-content" role="tab">
                                            <i class="fe fe-clock me-1"></i> {{ __('admin::lang.history') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="active-content" role="tabpanel">
                                        <livewire:admin::ads-content.content-table :placementId="$placement->id" />
                                    </div>
                                    <div class="tab-pane" id="history-content" role="tabpanel">
                                        <livewire:admin::ads-content.history-table :placementId="$placement->id" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <livewire:admin::ads-content.modal.create-content />
    <livewire:admin::ads-content.modal.create-product-content />
    <livewire:admin::ads-content.modal.update-product-content />
    <livewire:admin::ads-content.modal.delete-product-content />
    <livewire:admin::ads-content.modal.delete-content />
    <livewire:admin::ads-content.modal.update-content />
    <livewire:admin::ads-content.modal.reactivate />
    <livewire:admin::ads-content.modal.create-category-content />
    <livewire:admin::ads-content.modal.delete-category-content />
    <livewire:admin::ads-content.modal.create-merchant-content />
    <livewire:admin::ads-content.modal.update-merchant-content />

    <div class="modal fade" id="adsCropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary-transparent">
                    <h6 class="modal-title fw-bold text-primary" id="ads-crop-title">{{ __('admin::lang.Crop Image') }}</h6>
                    <button type="button" class="btn-close" id="ads-crop-close" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2" style="height: 62vh; background-color: transparent; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img id="ads-crop-image" src="" alt="" style="display: block; max-width: 100%; max-height: 100%;">
                </div>
                <div class="modal-footer bg-light py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-crop-action="rotate" data-crop-value="-90" title="Rotate -90">
                            <i class="fe fe-rotate-ccw"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-crop-action="rotate" data-crop-value="90" title="Rotate +90">
                            <i class="fe fe-rotate-cw"></i>
                        </button>
                        <div class="mx-1 text-muted" style="height: 18px; width: 1px; background: #ced4da;"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-crop-action="zoom" data-crop-value="0.1" title="Zoom In">
                            <i class="fe fe-zoom-in"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-crop-action="zoom" data-crop-value="-0.1" title="Zoom Out">
                            <i class="fe fe-zoom-out"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-sm btn-secondary" id="ads-crop-cancel">{{ __('admin::lang.Cancel') }}</button>
                        <button type="button" class="btn btn-sm btn-primary" id="ads-crop-apply">
                            <i class="fe fe-check me-1"></i>{{ __('admin::lang.Apply Crop') }}
                        </button>
                    </div>
                </div>
                <small id="ads-crop-hint" class="text-center text-muted py-1 px-2">{{ __('admin::lang.Crop Hint') }}</small>
            </div>
        </div>
    </div>

    <style>
        .ads-crop-preview {
            width: 88px;
            height: 44px;
            object-fit: contain;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        #adsCropModal .modal-body img {
            display: block;
            margin: 0 auto;
        }
    </style>
@endsection

@push('js')
<script>
    (function () {
        if (window.__adsContentCropperInit) return;
        window.__adsContentCropperInit = true;

        var Config = {
            image_desktop: { width: 1920, height: 600, label: 'Desktop' },
            image_mobile: { width: 768, height: 300, label: 'Mobile' },
            image_tablet: { width: 1024, height: 400, label: 'Tablet' }
        };

        var state = {
            cropper: null,
            property: null,
            fileName: null,
            objectUrl: null,
            input: null,
            cropEnabled: true,
            previewUrls: {},
        };

        function cropModalRoot() {
            if (state.input) {
                return state.input.closest('.modal') || document;
            }
            return document;
        }

        function applyDevices(devices) {
            if (!devices) return;
            state.cropEnabled = devices.allowCrop !== false;
            for (var key in devices) {
                if (!devices.hasOwnProperty(key) || !Config[key]) continue;
                if (devices[key].width) Config[key].width = parseInt(devices[key].width, 10) || Config[key].width;
                if (devices[key].height) Config[key].height = parseInt(devices[key].height, 10) || Config[key].height;
                if (devices[key].label) Config[key].label = devices[key].label;
            }
        }

        if (window.Livewire && Livewire.on) {
            Livewire.on('adsContentDevices', function (devices) { applyDevices(devices); });
        } else if (document.addEventListener) {
            document.addEventListener('livewire:load', function () {
                if (window.Livewire && Livewire.on) {
                    Livewire.on('adsContentDevices', function (devices) { applyDevices(devices); });
                }
            });
        }

        function getConfig(property) {
            return Config[property] || null;
        }

        function showPreview(property, url) {
            var root = cropModalRoot();
            var preview = root.querySelector('#ads-crop-preview-' + property);
            if (preview) {
                preview.src = url;
                preview.classList.remove('d-none');
            }
        }

        function uploadFile(file, property, previewUrl) {
            if (previewUrl) {
                if (state.previewUrls[property]) URL.revokeObjectURL(state.previewUrls[property]);
                state.previewUrls[property] = previewUrl;
                showPreview(property, previewUrl);
            }

            var root = cropModalRoot().closest ? cropModalRoot().closest('[wire\\:id]') : null;
            var component = root && root.__livewire;

            if (!component || !component.upload) {
                if (window.Swal) Swal.fire('Error', 'Livewire upload is not available', 'error');
                return;
            }

            component.upload(property, file, function () {
                if (state.input && state.input.files) {
                    var dt = new DataTransfer();
                    dt.items.add(state.input.files[0]);
                    state.input.files = dt.files;
                }
            }, function () {
                if (window.Swal) Swal.fire('Error', 'Upload failed, please try again', 'error');
            });
        }

        var cropImg = document.getElementById('ads-crop-image');
        var cropTitle = document.getElementById('ads-crop-title');
        var cropHint = document.getElementById('ads-crop-hint');
        var cropModalEl = document.getElementById('adsCropModal');

        function destroyCropper() {
            if (state.cropper) {
                state.cropper.destroy();
                state.cropper = null;
            }
            if (cropImg) cropImg.removeAttribute('src');
        }

        function closeCropper() {
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('adsCropModal'));
            modal.hide();
            destroyCropper();
            state.property = null;
            state.fileName = null;
            if (state.objectUrl) {
                URL.revokeObjectURL(state.objectUrl);
                state.objectUrl = null;
            }
        }

        function buildCropper(cfg) {
            if (state.cropper) state.cropper.destroy();

            state.cropper = new Cropper(cropImg, {
                aspectRatio: cfg.width / cfg.height,
                modal: true,
                guides: true,
                center: true,
                highlight: false,
                background: false,
                autoCropArea: 1,
                viewMode: 1,
                dragMode: 'move',
                scalable: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                checkOrientation: false,
                ready: function () {
                    var container = state.cropper.getContainerData();
                    if (!container) return;
                    state.cropper.setCropBoxData({
                        left: 0,
                        top: 0,
                        width: container.width,
                        height: container.height,
                    });
                    state.cropper.render();
                },
            });
        }

        function openCropper(file, property) {
            var cfg = getConfig(property);
            if (!cfg) return;

            if (!state.cropEnabled || file.type === 'image/gif' || !file.type.startsWith('image/')) {
                uploadFile(file, property, URL.createObjectURL(file));
                return;
            }

            if (state.objectUrl) URL.revokeObjectURL(state.objectUrl);
            state.objectUrl = URL.createObjectURL(file);
            state.property = property;
            state.fileName = file.name;
            cropTitle.textContent = cfg.label + '  (' + cfg.width + 'x' + cfg.height + 'px)';
            cropHint.innerHTML = cfg.width + 'x' + cfg.height + 'px — {{ __('admin::lang.Crop Hint') }}';

            destroyCropper();
            cropImg.src = state.objectUrl;

            var modal = bootstrap.Modal.getOrCreateInstance(cropModalEl);

            var initCropperAfterShown = function () {
                cropModalEl.removeEventListener('shown.bs.modal', initCropperAfterShown);
                if (cropImg.complete && cropImg.naturalWidth) {
                    buildCropper(cfg);
                } else {
                    cropImg.addEventListener('load', function onImgLoad() {
                        cropImg.removeEventListener('load', onImgLoad);
                        buildCropper(cfg);
                    });
                }
            };

            cropModalEl.addEventListener('shown.bs.modal', initCropperAfterShown);
            modal.show();
        }

        function applyCrop() {
            var property = state.property;
            var cfg = getConfig(property);
            if (!cfg || !state.cropper) return;

            var canvas = state.cropper.getCroppedCanvas({
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
                width: cfg.width,
                height: cfg.height,
            });

            canvas.toBlob(function (blob) {
                if (!blob) return;
                var ext = (blob.type.split('/')[1] || 'png');
                var base = (state.fileName || 'image').replace(/\.[^/.]+$/, '');
                var croppedFile = new File([blob], base + '.' + ext, {
                    type: blob.type,
                    lastModified: Date.now(),
                });
                var previewUrl = URL.createObjectURL(blob);
                closeCropper();
                uploadFile(croppedFile, property, previewUrl);
            }, 'image/webp', 0.85);
        }

        document.addEventListener('change', function (e) {
            var input = e.target;
            if (!input || !input.matches || !input.matches('input[type="file"][data-property]')) return;
            if (!input.files || !input.files[0]) return;
            state.input = input;
            openCropper(input.files[0], input.dataset.property);
        });

        document.getElementById('ads-crop-apply').addEventListener('click', applyCrop);
        document.getElementById('ads-crop-cancel').addEventListener('click', closeCropper);
        document.getElementById('ads-crop-close').addEventListener('click', closeCropper);
        document.querySelectorAll('#adsCropModal [data-crop-action]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!state.cropper) return;
                var action = btn.dataset.cropAction;
                var value = parseFloat(btn.dataset.cropValue);
                if (action === 'rotate') state.cropper.rotate(value);
                if (action === 'zoom') state.cropper.zoom(value);
            });
        });

        cropModalEl.addEventListener('hidden.bs.modal', function () {
            destroyCropper();
            state.property = null;
            state.fileName = null;
            if (state.objectUrl) {
                URL.revokeObjectURL(state.objectUrl);
                state.objectUrl = null;
            }
        });

        ['createContentModal', 'updateContentModal'].forEach(function (id) {
            var modalEl = document.getElementById(id);
            if (!modalEl) return;
            modalEl.addEventListener('hidden.bs.modal', function () {
                modalEl.querySelectorAll('input[data-property]').forEach(function (input) {
                    input.value = '';
                });
                modalEl.querySelectorAll('.ads-crop-preview').forEach(function (img) {
                    var property = img.id.replace('ads-crop-preview-', '');
                    if (state.previewUrls[property]) {
                        URL.revokeObjectURL(state.previewUrls[property]);
                        delete state.previewUrls[property];
                    }
                    img.classList.add('d-none');
                    img.src = '';
                });
            });
        });
    })();
</script>
@endpush
