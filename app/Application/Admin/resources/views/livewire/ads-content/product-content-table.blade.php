<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" wire:model="statusFilter" style="width: auto;">
                <option value="all">{{ __('admin::lang.All Status') }}</option>
                <option value="active">{{ __('admin::lang.Active') }}</option>
                <option value="inactive">{{ __('admin::lang.Inactive') }}</option>
                <option value="scheduled">{{ __('admin::lang.Scheduled') }}</option>
                <option value="expired">{{ __('admin::lang.Expired') }}</option>
            </select>

            @if($placement->type->value === 'product_tabs' && !empty($this->groups))
                <select class="form-select form-select-sm" wire:model="groupFilter" style="width: auto;">
                    <option value="all">{{ __('admin::lang.All Groups') }}</option>
                    @foreach($this->groups as $group)
                        <option value="{{ $group['key'] }}">{{ $group['title'][app()->getLocale()] ?? $group['title']['en'] }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="d-flex gap-2">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary {{ $viewMode === 'grid' ? 'active' : '' }}" wire:click="toggleViewMode('grid')">
                    <i class="fe fe-grid"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary {{ $viewMode === 'table' ? 'active' : '' }}" wire:click="toggleViewMode('table')">
                    <i class="fe fe-list"></i>
                </button>
            </div>

            <button class="btn btn-sm btn-primary" wire:click="openCreateModal">
                <i class="fe fe-plus me-1"></i>{{ __('admin::lang.Add Products') }}
            </button>
        </div>
    </div>

    @if(count($productContents) > 0)
        @if($viewMode === 'table')
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>{{ __('admin::lang.Product') }}</th>
                            @if($placement->type->value === 'product_tabs')
                                <th>{{ __('admin::lang.Group') }}</th>
                            @endif
                            <th>{{ __('admin::lang.Status') }}</th>
                            <th>{{ __('admin::lang.Priority') }}</th>
                            <th>{{ __('admin::lang.Schedule') }}</th>
                            <th style="width: 150px;">{{ __('admin::lang.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productContents as $content)
                            <tr>
                                <td>{{ $content->id }}</td>
                                <td>
                                    @if($content->product)
                                        <div class="d-flex align-items-center">
                                            @if($content->product->image)
                                                <img src="{{ $content->product->image }}" alt="" class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $content->product->getTranslation('title', app()->getLocale()) }}</div>
                                                <small class="text-muted">ID: {{ $content->product_id }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-danger">Product not found</span>
                                    @endif
                                </td>
                                @if($placement->type->value === 'product_tabs')
                                    <td>
                                        @if($content->group_key)
                                            @php
                                                $group = collect($this->groups)->firstWhere('key', $content->group_key);
                                            @endphp
                                            @if($group)
                                                <span class="badge bg-primary-transparent text-primary">
                                                    {{ $group['title'][app()->getLocale()] ?? $group['title']['en'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ $content->group_key }}</span>
                                            @endif
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    @switch($content->status->value)
                                        @case('active')
                                            <span class="badge bg-success">{{ $content->status->label() }}</span>
                                            @break
                                        @case('inactive')
                                            <span class="badge bg-secondary">{{ $content->status->label() }}</span>
                                            @break
                                        @case('scheduled')
                                            <span class="badge bg-warning">{{ $content->status->label() }}</span>
                                            @break
                                        @case('expired')
                                            <span class="badge bg-danger">{{ $content->status->label() }}</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $content->priority }}</td>
                                <td>
                                    @if($content->start_date || $content->end_date)
                                        <small>
                                            @if($content->start_date)
                                                <div>{{ __('admin::lang.From') }}: {{ $content->start_date->format('Y-m-d') }}</div>
                                            @endif
                                            @if($content->end_date)
                                                <div>{{ __('admin::lang.To') }}: {{ $content->end_date->format('Y-m-d') }}</div>
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info" wire:click="openUpdateModal({{ $content->id }})" title="{{ __('admin::lang.Edit') }}"
                                            data-edit-loading>
                                            <i class="fe fe-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="openDeleteModal({{ $content->id }})" title="{{ __('admin::lang.Delete') }}">
                                            <i class="fe fe-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="row g-3">
                @foreach($productContents as $content)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="card border h-100">
                            @if($content->product && $content->product->image)
                                <img src="{{ $content->product->image }}" class="card-img-top" alt="" style="height: 150px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fe fe-package fs-1 text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body p-2">
                                <h6 class="card-title small mb-1 text-truncate" title="{{ $content->product?->getTranslation('title', app()->getLocale()) }}">
                                    {{ $content->product?->getTranslation('title', app()->getLocale()) ?? 'N/A' }}
                                </h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="badge bg-{{ $content->status->value === 'active' ? 'success' : 'secondary' }}">
                                        {{ $content->status->label() }}
                                    </small>
                                    <small class="text-muted">P: {{ $content->priority }}</small>
                                </div>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-info" wire:click="openUpdateModal({{ $content->id }})"
                                        data-edit-loading>
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" wire:click="openDeleteModal({{ $content->id }})">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="text-center p-5 border rounded bg-light">
            <i class="fe fe-package fs-50 text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('admin::lang.No products added yet') }}</h5>
            <button class="btn btn-primary mt-3" wire:click="openCreateModal">
                <i class="fe fe-plus me-1"></i>{{ __('admin::lang.Add Products') }}
            </button>
        </div>
    @endif
</div>
