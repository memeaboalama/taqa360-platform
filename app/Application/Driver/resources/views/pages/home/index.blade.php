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
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.preparingOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['preparingOrdersCount'] }}</h4>
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
                        <span class="avatar avatar-md avatar-rounded bg-primary"><i class="ti ti-truck-delivery fs-16"></i></span>
                        <p class="text-muted mb-0 mt-2">{{ __('driver::home.onWayOrders') }}</p>
                        <h4 class="fw-semibold mt-1 mb-0">{{ $stats['onWayOrdersCount'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <livewire:driver::home.assignedorders />
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

    var driverToastError = @json(session('error'));
    if (driverToastError) {
        Swal.fire({
            toast: true,
            position: '{{ app()->getLocale() === 'ar' ? 'top-start' : 'top-end' }}',
            icon: 'error',
            title: driverToastError,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        });
    }

    document.addEventListener('submit', function (event) {
        var form = event.target.closest('.order-action-form');
        if (!form) return;

        var btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        btn.disabled = true;
        btn.innerHTML = '@lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>';
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

    window.addEventListener('driver-order-delivery-completed', function () {
        window.location.reload();
    });

    function syncAckForms() {
        document.querySelectorAll('[data-ack-form]').forEach(function (form) {
            var checks = form.querySelectorAll('.js-ack-check');
            var submitBtn = form.querySelector('.js-ack-submit');
            if (!submitBtn || !checks.length) return;
            var allChecked = Array.prototype.every.call(checks, function (cb) {
                return cb.checked;
            });
            submitBtn.disabled = !allChecked;
        });
    }

    document.addEventListener('change', function (event) {
        if (!event.target.classList || !event.target.classList.contains('js-ack-check')) return;
        syncAckForms();
    });

    if (window.Livewire && Livewire.hook) {
        Livewire.hook('message.processed', function () {
            syncAckForms();
        });
    }
    document.addEventListener('livewire:load', function () {
        if (window.Livewire && Livewire.hook) {
            Livewire.hook('message.processed', syncAckForms);
        }
        syncAckForms();
    });
</script>
@endpush
