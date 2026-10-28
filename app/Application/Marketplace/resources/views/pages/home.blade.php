@extends('marketplace::layout.layout')

@section('title', __('marketplace::home.Merchant |Home'))
@section('content')
    @if(session('login_welcome'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="ri-checkbox-circle-line me-2 fs-18"></i>
            <div>{{ session('login_welcome') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-xxl-12 col-xl-12">
            <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xxl-4 col-lg-6 col-md-6">
                        <div class="card custom-card crm-highlight-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold fs-18 text-fixed-white mb-2">{{ __('marketplace::home.welcome') }} </div>
                                        <span class="d-block fs-5 text-white"><span class="">{{ resolve('_InitialMerchantName') }}</span> | <span class="">{{ resolve('_InitialMerchantCompanyName') }}</span>.</span>
                                    </div>
                                    <div>
                                        <div id="crm-main"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @MerchantHasRole(['show homepage_general'])
                    <div class="col-xxl-4 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-info">
                                            <i class="ti ti-gift fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.Total Orders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['ordersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-info" href="{{ route('marketplace:orders') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-primary">
                                            <i class="ti ti-gift fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.pendingOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['pendingOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-primary" href="{{ route('marketplace:orders', 'pending') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-primary">
                                            <i class="ti ti-gift fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.readyOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['readyOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-primary" href="{{ route('marketplace:orders', 'ready') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                            <i class="ti ti-gift fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.completedOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['completedOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-success" href="{{ route('marketplace:orders', 'completed') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-danger">
                                            <i class="ti ti-gift fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.cancelledOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['cancelledOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-danger" href="{{ route('marketplace:orders', 'cancelled') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-danger">
                                            <i class="ti ti-gift fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.expiredOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['expiredOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-danger" href="{{ route('marketplace:orders', 'expired') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-secondary">
                                                            <i class="ti ti-leaf fs-16"></i>
                                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.Total Branches') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['branchesCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-revenue"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-secondary" href="{{ route('marketplace:branches') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                                            <i class="ti ti-users fs-16"></i>
                                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.Total Employees') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['employeesCount'] }}</h4>
                                            </div>
                                            <div id="crm-conversion-ratio"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-success" href="{{ route('marketplace:employees') }}">{{ __('marketplace::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-warning">
                                                            <i class="ti ti-section fs-16"></i>
                                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('marketplace::home.Total Items') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['itemCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-deals"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endMerchantHasRole
                </div>
            </div>
            @MerchantHasRole(['show orders_general'])
                <div class="col-xl-9">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                {{ __('marketplace::home.Orders Statistics') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <livewire:marketplace::home.order.table.all/>
                            </div>
                        </div>
                    </div>
                </div>
                @endMerchantHasRole
                @MerchantHasRole(['show_employees'])
                <div class="col-xl-3">
                    <div class="card custom-card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">
                                {{ __('marketplace::home.Top Employees') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled crm-top-deals mb-0">
                                @foreach($topEmployees as $employee)
                                    <li>
                                        <div class="d-flex align-items-top flex-wrap">
                                            <div class="flex-fill ">
                                                <p class="fw-semibold mb-0">{{ $employee->name }}</p>
                                                <span class="text-muted fs-12">{{ $employee->email }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endMerchantHasRole
        </div>
    </div>
@endsection
