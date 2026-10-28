<div>
    <div class="mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <input type="text"
                    class="form-control form-control-sm"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('admin::lang.Search by name') }}...">
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted small">{{ __('admin::lang.showing_inactive_content') }}</span>
            </div>
        </div>
    </div>

    @if(collect($contents)->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('admin::lang.preview') }}</th>
                        <th>{{ __('admin::lang.Name') }} / {{ __('admin::lang.Title') }}</th>
                        <th>{{ __('admin::lang.Status') }}</th>
                        <th>{{ __('admin::lang.last_updated') }}</th>
                        <th width="150">{{ __('admin::lang.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $item)
                        <tr>
                            <td class="align-middle" style="width: 80px;">
                                @if(isset($item->content_type) && $item->content_type === 'product')
                                    @php $product = $item->displayable_product; @endphp
                                    @if($product && $product->image_path)
                                        <img src="{{ $product->image_path }}" class="rounded border" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div style="width: 60px; height: 40px;" class="bg-light rounded border d-flex align-items-center justify-content-center">
                                            <i class="fe fe-package text-muted"></i>
                                        </div>
                                    @endif
                                @else
                                    @if($item->desktop_image_url)
                                        <img src="{{ $item->desktop_image_url }}" alt="{{ $item->name }}" style="width: 60px; height: 40px; object-fit: cover;" class="rounded border">
                                    @else
                                        <div style="width: 60px; height: 40px;" class="bg-light rounded border d-flex align-items-center justify-content-center">
                                            <i class="fe fe-image text-muted"></i>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="align-middle">
                                @if(isset($item->content_type) && $item->content_type === 'product')
                                    @php $product = $item->displayable_product; @endphp
                                    <h6 class="mb-1 fw-semibold">{{ $product ? $product->title : 'N/A' }}</h6>
                                    <small class="text-muted">Product ID: {{ $item->product_id }}</small>
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
                                <small class="text-muted">{{ $item->updated_at->diffForHumans() }}</small>
                            </td>
                            <td class="align-middle">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-success"
                                            wire:click="reactivate({{ $item->id }}, '{{ $item->content_type }}')"
                                            title="{{ __('admin::lang.Reactivate') }}">
                                        <i class="fe fe-rotate-ccw"></i> {{ __('admin::lang.Reactivate') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fe fe-info me-2"></i>
            {{ __('admin::lang.no_history_found') }}
        </div>
    @endif
</div>
