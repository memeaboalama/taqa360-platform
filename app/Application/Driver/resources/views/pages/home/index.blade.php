@extends('driver::layout.layout')

@section('title', __('driver::home.home'))

@section('content')
    @if(session('login_welcome'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center col-xxl-12" role="alert">
            <i class="ri-checkbox-circle-line me-2 fs-18"></i>
            <div>{{ session('login_welcome') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-4">
                <div class="card custom-card crm-highlight-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold fs-18 text-fixed-white mb-2">{{ __('driver::home.welcome') }}</div>
                                <span class="d-block fs-5 text-white">
                                    <span>{{ $driver->name }}</span>
                                    @if($driver->company)
                                        | <span>{{ $driver->company->company_name }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header bg-white">
                        <h5 class="fw-bold mb-0">{{ __('driver::home.profile') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('driver::home.Full Name') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('driver::home.Email Address') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->email }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('driver::home.Phone Number') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->phone }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('admin::home.Vehicle Type') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->carType?->title }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('admin::home.Vehicle Registration Plate') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->carNumber }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('admin::home.Vehicle ID') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->vehicleSequenceNumber }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('admin::home.Region') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->region?->title }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('admin::home.City') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->city?->title }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-0">{{ __('admin::home.Company Name') }}</p>
                                <p class="fw-semibold mb-0">{{ $driver->company?->company_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <span class="avatar avatar-md avatar-rounded bg-info"><i class="ti ti-gift fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.Total Orders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['ordersCount'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <span class="avatar avatar-md avatar-rounded bg-warning"><i class="ti ti-clock fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.pendingOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['pendingOrdersCount'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <span class="avatar avatar-md avatar-rounded bg-primary"><i class="ti ti-package fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.readyOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['readyOrdersCount'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <span class="avatar avatar-md avatar-rounded bg-success"><i class="ti ti-check fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.completedOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['completedOrdersCount'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <span class="avatar avatar-md avatar-rounded bg-danger"><i class="ti ti-x fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.cancelledOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['cancelledOrdersCount'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <span class="avatar avatar-md avatar-rounded bg-danger"><i class="ti ti-alert-triangle fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.expiredOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['expiredOrdersCount'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header bg-white justify-content-between">
                        <div class="card-title">{{ __('driver::home.orders') }}</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">{{ __('driver::home.Records Per Page') }}</span>
                            <select class="form-select form-select-sm" style="width:auto;"
                                    onchange="window.location.href = this.value">
                                @foreach([10,25,50,100] as $count)
                                    <option value="{{ request()->url() }}?perPage={{ $count }}"
                                        {{ (int) request('perPage', 10) === $count ? 'selected' : '' }}>
                                        {{ $count }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentOrders->count())
                            <div class="table-responsive">
                                <table class="table text-nowrap align-middle">
                                     <thead>
                                        <tr>
                                            <th>{{ __('driver::home.Order No') }}</th>
                                            <th>{{ __('driver::home.userName') }}</th>
                                            <th>{{ __('driver::home.Total Price') }}</th>
                                            <th>{{ __('driver::home.status') }}</th>
                                            <th>{{ __('driver::home.date') }}</th>
                                            <th>{{ __('driver::home.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td><span class="fw-semibold text-primary">#{{ $order->code }}</span></td>
                                                <td>{{ $order->user?->name }}</td>
                                                <td>{{ number_format($order->total_after_vat, 2) }} SAR</td>
                                                <td>
                                                    <span class="badge {{ $order->status_color }}">
                                                        {{ $order->status ? __("driver::home.order_status_" . (is_object($order->status) ? $order->status->value : $order->status)) : '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @php
                                                        $statusVal = is_object($order->status) ? $order->status->value : $order->status;
                                                    @endphp
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-info-transparent rounded-pill"
                                                           style="width:1.75rem;height:1.75rem;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;"
                                                           title="{{ __('driver::home.View') }}"
                                                           onclick="Livewire.emit('showOrderDetails', {{ $order->id }})"><i class="ri-eye-line" style="margin:0"></i></a>
                                                    @if($statusVal === 'pending' || $statusVal === 'upcoming')
                                                        <form action="{{ route('driver:orders.accept', $order->id) }}" method="POST" class="d-inline order-action-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="ri-check-line me-1"></i> قبول الطلب
                                                            </button>
                                                        </form>
                                                    @elseif($statusVal === 'ready')
                                                        <form action="{{ route('driver:orders.updateStatus', $order->id) }}" method="POST" class="d-inline order-action-form">
                                                            @csrf
                                                            <input type="hidden" name="status" value="on-way">
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="ri-truck-line me-1"></i> جاري التوصيل
                                                            </button>
                                                        </form>
                                                    @elseif($statusVal === 'on-way')
                                                        <form action="{{ route('driver:orders.updateStatus', $order->id) }}" method="POST" class="d-inline order-action-form">
                                                            @csrf
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="ri-checkbox-circle-line me-1"></i> تم التسليم
                                                            </button>
                                                        </form>
                                                    @elseif($statusVal === 'started-work')
                                                        <span class="badge bg-warning text-dark"><i class="ri-time-line me-1"></i> جاري التجهيز بالمحل</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $recentOrders->links('pagination::bootstrap-5') }}
                        @else
                            <p class="text-muted mb-0">{{ __('driver::home.no_orders') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:driver::home.order.modal.show />
@endsection

@push('js')
<script>
    var driverToastMsg = @json(session('success'));
    if (driverToastMsg) {
        Swal.fire({
            toast: true,
            position: '{{ app()->getLocale() === 'ar' ? 'top-start' : 'top-end' }}',
            icon: 'success',
            title: driverToastMsg,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    document.querySelectorAll('.order-action-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            btn.innerHTML = '@lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>';
        });
    });

    document.addEventListener('submit', function (e) {
        var form = e.target.closest('#acceptOrderForm');
        if (!form) return;
        var modalEl = form.closest('.modal');
        if (!modalEl) return;
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '@lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>';
        }
        modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
            btn.disabled = true;
        });
    });
</script>
@endpush