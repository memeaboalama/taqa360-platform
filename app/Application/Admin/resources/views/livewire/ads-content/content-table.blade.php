<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0 text-primary fw-bold">{{ __('admin::lang.Content Items') }}
                <span class="badge bg-primary-transparent text-primary ms-2">{{ collect($contents)->count() }}</span>
            </h5>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" wire:model="statusFilter" style="width: 150px;">
                <option value="all">{{ __('admin::lang.All') }}</option>
                @foreach($statusOptions as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary {{ $viewMode === 'grid' ? 'active' : '' }}" wire:click="toggleViewMode('grid')">
                    <i class="fe fe-grid"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary {{ $viewMode === 'table' ? 'active' : '' }}" wire:click="toggleViewMode('table')">
                    <i class="fe fe-list"></i>
                </button>
            </div>

            <button class="btn btn-sm btn-primary" wire:click="openCreateModal">
                <i class="fe fe-plus me-1"></i>{{ __('admin::home.Add New') }}
            </button>
        </div>
    </div>

    @if(count($contents) > 0)
        @if($viewMode === 'grid')
            <div class="row row-sm" id="sortable-grid" wire:ignore>
                @foreach($contents as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 sortable-item" data-id="{{ $item->id }}">
                        <div class="card custom-card h-100 border shadow-sm hover-card">
                            <div class="position-relative">
                                @php $deviceImages = []; @endphp
                                @if(isset($item->content_type) && $item->content_type === 'product')
                                    @php $product = $item->displayable_product; @endphp
                                    @if($product && $product->image_path)
                                        <img src="{{ $product->image_path }}" class="card-img-top" alt="{{ $product->title }}" style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                            <i class="fe fe-package fs-40 text-muted"></i>
                                        </div>
                                    @endif
                                    @elseif(isset($item->content_type) && $item->content_type === 'category')
                                        @php $category = $item->displayable_category; @endphp
                                        @if($category && $category->image)
                                            <img src="{{ $category->image }}" class="card-img-top" alt="{{ $category->title }}" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                <i class="fe fe-list fs-40 text-muted"></i>
                                            </div>
                                        @endif
                                    @else
                                        @php
                                            $deviceImages = [
                                                'desktop' => $item->desktop_image_url,
                                                'tablet' => $item->tablet_image_url,
                                                'mobile' => $item->mobile_image_url,
                                            ];
                                        @endphp
                                        @if($deviceImages['desktop'] || $deviceImages['tablet'] || $deviceImages['mobile'])
                                            <div class="banner-device-previews">
                                                @foreach($deviceImages as $deviceKey => $deviceImg)
                                                    <div class="banner-device-preview {{ $loop->first ? 'active' : '' }}" data-device="{{ $deviceKey }}" data-image="{{ $deviceImg }}">
                                                        @if($deviceImg)
                                                            <img src="{{ $deviceImg }}" alt="{{ $item->title }}" class="card-img-top w-100" style="height: 180px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                                <i class="fe fe-image fs-40 text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                <i class="fe fe-image fs-40 text-muted"></i>
                                            </div>
                                        @endif
                                    @endif

                                <div class="position-absolute bottom-0 start-0 p-2 w-100">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @foreach([
                                            'desktop' => ['fe-monitor', 'admin::lang.Desktop'],
                                            'tablet' => ['fe-tablet', 'admin::lang.Tablet'],
                                            'mobile' => ['fe-smartphone', 'admin::lang.Mobile'],
                                        ] as $deviceKey => [$icon, $label])
                                            @if(isset($deviceImages) && ($deviceImages[$deviceKey] ?? null))
                                                <span class="badge bg-dark text-white shadow-sm banner-device-tab {{ $loop->first ? 'active' : '' }}" data-device="{{ $deviceKey }}" style="cursor: pointer;">
                                                    <i class="fe {{ $icon }} me-1"></i>{{ __($label) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                </div>

                                <div class="position-absolute top-0 end-0 p-2">
                                    @if($item->status->value === 'active')
                                        <span class="badge bg-success shadow-sm">{{ $item->status->label() }}</span>
                                    @elseif($item->status->value === 'scheduled')
                                        <span class="badge bg-warning shadow-sm">{{ $item->status->label() }}</span>
                                    @else
                                        <span class="badge bg-secondary shadow-sm">{{ $item->status->label() }}</span>
                                    @endif
                                </div>

                                <div class="position-absolute top-0 start-0 p-2">
                                    <span class="badge bg-white text-dark shadow-sm drag-handle cursor-move" title="{{ __('admin::lang.Drag to reorder') }}">
                                        <i class="fe fe-move"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @if(isset($item->content_type) && $item->content_type === 'product')
                                        @php $product = $item->displayable_product; @endphp
                                        <h6 class="card-title mb-0 fw-bold text-dark">{{ $product ? $product->title : 'N/A' }}</h6>
                                    @elseif(isset($item->content_type) && $item->content_type === 'category')
                                        @php $category = $item->displayable_category; @endphp
                                        <h6 class="card-title mb-0 fw-bold text-dark">{{ $category ? $category->title : 'N/A' }}</h6>
                                    @else
                                        <h6 class="card-title mb-0 fw-bold text-dark">{{ $item->name }}</h6>
                                    @endif
                                    <span class="badge bg-light text-primary border priority-badge">
                                        <i class="fe fe-hash me-1"></i>{{ $item->priority }}
                                    </span>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-flex align-items-center text-muted small mb-3">
                                        <i class="fe fe-calendar me-2"></i>
                                        {{ $item->start_date ? $item->start_date->format('Y-m-d') : __('admin::lang.No date') }}
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary flex-grow-1" wire:click="openUpdateModal({{ $item->id }})"
                                                data-edit-loading data-edit-loading-label="{{ __('admin::lang.Edit') }}">
                                            <i class="fe fe-edit me-1"></i>{{ __('admin::lang.Edit') }}
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" wire:click="openDeleteModal({{ $item->id }})">
                                            <i class="fe fe-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-nowrap border-bottom" id="sortable-table">
                    <thead class="bg-light">
                        <tr>
                            <th width="40" class="text-center"><i class="fe fe-move"></i></th>
                            <th width="80">{{ __('admin::lang.Preview') }}</th>
                            <th>{{ __('admin::lang.Details') }}</th>
                            <th width="100">{{ __('admin::lang.Status') }}</th>
                            <th width="140">{{ __('admin::lang.Schedule') }}</th>
                            <th width="80" class="text-center">{{ __('admin::lang.Priority') }}</th>
                            <th width="120" class="text-center">{{ __('admin::lang.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contents as $item)
                            <tr class="sortable-row" data-id="{{ $item->id }}">
                                <td class="text-center align-middle">
                                    <span class="drag-handle cursor-move text-muted">
                                        <i class="fe fe-menu fs-18"></i>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if(isset($item->content_type) && $item->content_type === 'product')
                                        @php $product = $item->displayable_product; @endphp
                                        @if($product && $product->image_path)
                                            <img src="{{ $product->image_path }}" alt="{{ $product->title }}" style="width: 60px; height: 40px; object-fit: cover;" class="rounded border">
                                        @else
                                            <div style="width: 60px; height: 40px;" class="bg-light rounded border d-flex align-items-center justify-content-center">
                                                <i class="fe fe-package text-muted"></i>
                                            </div>
                                        @endif
                                    @elseif(isset($item->content_type) && $item->content_type === 'category')
                                        @php $category = $item->displayable_category; @endphp
                                        @if($category && $category->image)
                                            <img src="{{ $category->image }}" alt="{{ $category->title }}" style="width: 60px; height: 40px; object-fit: cover;" class="rounded border">
                                        @else
                                            <div style="width: 60px; height: 40px;" class="bg-light rounded border d-flex align-items-center justify-content-center">
                                                <i class="fe fe-list text-muted"></i>
                                            </div>
                                        @endif
                                    @elseif(isset($item->content_type) && $item->content_type === 'merchant')
                                        @php $merchant = $item->displayable_merchant; @endphp
                                        @if($merchant && $merchant->logo)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($merchant->logo) }}" alt="{{ $merchant->name }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded border">
                                        @else
                                            <div style="width: 50px; height: 50px;" class="bg-light rounded border d-flex align-items-center justify-content-center">
                                                <i class="fe fe-store text-muted"></i>
                                            </div>
                                        @endif
                                    @else
                                        @php
                                            $tabletDeviceImages = [
                                                'desktop' => $item->desktop_image_url,
                                                'tablet' => $item->tablet_image_url,
                                                'mobile' => $item->mobile_image_url,
                                            ];
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="banner-device-previews">
                                                @foreach($tabletDeviceImages as $deviceKey => $deviceImg)
                                                    <div class="banner-device-preview {{ $loop->first ? 'active' : '' }}" data-device="{{ $deviceKey }}" data-image="{{ $deviceImg }}">
                                                        @if($deviceImg)
                                                            <img src="{{ $deviceImg }}" alt="{{ $item->name }}" style="width: 60px; height: 40px; object-fit: cover;" class="rounded border">
                                                        @else
                                                            <div style="width: 60px; height: 40px;" class="bg-light rounded border d-flex align-items-center justify-content-center">
                                                                <i class="fe fe-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="d-flex flex-column gap-1">
                                                @foreach([
                                                    'desktop' => 'fe-monitor',
                                                    'tablet' => 'fe-tablet',
                                                    'mobile' => 'fe-smartphone',
                                                ] as $deviceKey => $icon)
                                                    @if($tabletDeviceImages[$deviceKey] ?? null)
                                                        <span class="badge bg-dark text-white shadow-sm banner-device-tab {{ $loop->first ? 'active' : '' }}" data-device="{{ $deviceKey }}" style="cursor: pointer; font-size: 0.65rem; line-height: 1;">
                                                            <i class="fe {{ $icon }}"></i>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if(isset($item->content_type) && $item->content_type === 'product')
                                        @php $product = $item->displayable_product; @endphp
                                        <h6 class="mb-1 fw-semibold">{{ $product ? $product->title : 'N/A' }}</h6>
                                        <small class="text-muted">Product ID: {{ $item->product_id }}</small>
                                    @elseif(isset($item->content_type) && $item->content_type === 'category')
                                        @php $category = $item->displayable_category; @endphp
                                        <h6 class="mb-1 fw-semibold">{{ $category ? $category->title : 'N/A' }}</h6>
                                        <small class="text-muted">Category ID: {{ $item->category_id }}</small>
                                    @elseif(isset($item->content_type) && $item->content_type === 'merchant')
                                        @php $merchant = $item->displayable_merchant; @endphp
                                        <h6 class="mb-1 fw-semibold">{{ $merchant ? $merchant->name : 'N/A' }}</h6>
                                        <small class="text-muted">Merchant ID: {{ $item->merchant_id }}</small>
                                    @else
                                        <h6 class="mb-1 fw-semibold">{{ $item->name }}</h6>
                                        <small class="text-muted">{{ isset($item->title) ? Str::limit($item->title, 60) : '' }}</small>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($item->status->value == 'active')
                                        <span class="badge bg-success">
                                            <i class="fe fe-check-circle me-1"></i>{{ $item->status->label() }}
                                        </span>
                                    @elseif($item->status->value == 'scheduled')
                                        <span class="badge bg-warning">
                                            <i class="fe fe-clock me-1"></i>{{ $item->status->label() }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fe fe-circle me-1"></i>{{ $item->status->label() }}
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($item->start_date)
                                        <div class="d-flex align-items-center">
                                            <i class="fe fe-calendar me-2 text-muted"></i>
                                            <div>
                                                <small class="d-block">{{ $item->start_date->format('Y-m-d') }}</small>
                                                @if($item->end_date)
                                                    <small class="d-block text-muted">{{ $item->end_date->format('Y-m-d') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge bg-primary priority-badge" style="font-size: 0.85rem; padding: 0.35rem 0.65rem;">
                                        <i class="fe fe-hash"></i> {{ $item->priority }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary" wire:click="openUpdateModal({{ $item->id }})" title="{{ __('admin::lang.Edit') }}"
                                                    data-edit-loading>
                                            <i class="fe fe-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="openDeleteModal({{ $item->id }})" title="{{ __('admin::lang.Delete') }}">
                                            <i class="fe fe-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="text-center p-5 border rounded bg-light">
            <i class="fe fe-image fs-50 text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('admin::lang.No content items found') }}</h5>
            <p class="text-muted mb-4">{{ __('admin::lang.Add your first item to this placement') }}</p>
            <button class="btn btn-primary" wire:click="openCreateModal">
                <i class="fe fe-plus me-1"></i>{{ __('admin::lang.Add New') }}
            </button>
        </div>
    @endif
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        let sortableInstances = [];

        function initDeviceSwitcher() {
            document.querySelectorAll('.banner-device-previews').forEach((previews) => {
                previews.querySelectorAll('.banner-device-preview:not(.active)').forEach((p) => {
                    p.style.display = 'none';
                });
            });

            document.querySelectorAll('.banner-device-tab').forEach((tab) => {
                tab.removeEventListener('click', handleDeviceTabClick);
                tab.addEventListener('click', handleDeviceTabClick);
            });
        }

        function handleDeviceTabClick(e) {
            const tab = e.currentTarget;
            const container = tab.closest('.card, .d-flex') || document;
            const device = tab.dataset.device;

            container.querySelectorAll('.banner-device-tab').forEach((t) => {
                t.classList.remove('active');
                t.classList.add('badge-dark');
            });
            tab.classList.add('active');
            tab.classList.remove('badge-dark');

            container.querySelectorAll('.banner-device-preview').forEach((p) => {
                p.style.display = p.dataset.device === device ? '' : 'none';
            });
        }

        function initSortable() {
            sortableInstances.forEach(instance => instance.destroy());
            sortableInstances = [];

            const tableEl = document.querySelector('#sortable-table tbody');
            if (tableEl) {
                sortableInstances.push(new Sortable(tableEl, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    onEnd: function (evt) {
                        updateOrder(tableEl.querySelectorAll('tr[data-id]'));
                    }
                }));
            }

            const gridEl = document.getElementById('sortable-grid');
            if (gridEl) {
                sortableInstances.push(new Sortable(gridEl, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    swapThreshold: 0.5,
                    invertSwap: true,
                    onEnd: function (evt) {
                        updateOrder(gridEl.querySelectorAll('[data-id]'));
                    }
                }));
            }
        }

        function updateOrder(items) {
            let order = [];
            items.forEach((item, index) => {
                const newPriority = index + 1;
                order.push({
                    id: parseInt(item.dataset.id),
                    priority: newPriority
                });

                const badge = item.querySelector('.priority-badge');
                if (badge) {
                    const icon = badge.querySelector('i');
                    badge.innerHTML = (icon ? icon.outerHTML + ' ' : '') + newPriority;
                    badge.classList.add('pulse-badge');
                    setTimeout(() => badge.classList.remove('pulse-badge'), 500);
                }
            });

            @this.call('reorder', order);
        }

        initSortable();
        initDeviceSwitcher();
        Livewire.hook('message.processed', () => setTimeout(() => { initSortable(); initDeviceSwitcher(); }, 200));
    });
</script>
@endpush

@push('css')
<style>
    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        border-color: #6259ca !important;
    }

    .drag-handle {
        cursor: grab;
        transition: all 0.2s ease;
    }

    .drag-handle:active {
        cursor: grabbing;
        background-color: #6259ca !important;
        color: white !important;
    }

    .sortable-ghost {
        opacity: 0.4;
        background-color: #f0f0f8 !important;
        border: 2px dashed #cbd5e0 !important;
    }

    #sortable-grid .sortable-ghost {
        opacity: 0.4;
        transform: scale(0.98);
    }

    .priority-badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
    }

    .banner-device-tab.active {
        background-color: rgb(var(--primary-rgb)) !important;
    }

    .banner-device-previews .banner-device-preview:not(.active) {
        display: none;
    }
</style>
@endpush
