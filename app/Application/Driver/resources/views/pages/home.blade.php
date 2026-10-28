@extends('driver::layout.layout')

@section('title', __('driver::home.Driver |Home'))
@section('content')
    <div class="col-xxl-12 col-xl-12">
            <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xxl-4 col-lg-6 col-md-6">
                        <div class="card custom-card crm-highlight-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold fs-18 text-fixed-white mb-2">{{ __('driver::home.welcome') }} </div>
                                        <span class="d-block fs-5 text-white"><span class="">{{ resolve('_InitialDriverName') }}</span> | <span class="">{{ resolve('_InitialDriverCompanyName') }}</span>.</span>
                                    </div>
                                    <div>
                                        <div id="crm-main"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @DriverHasRole(['show homepage_general'])
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
                                                <p class="text-muted mb-0">{{ __('driver::home.Total Orders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['ordersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-info" href="{{ route('driver:orders') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.pendingOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['pendingOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-primary" href="{{ route('driver:orders', 'pending') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.readyOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['readyOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-primary" href="{{ route('driver:orders', 'ready') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.completedOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['completedOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-success" href="{{ route('driver:orders', 'completed') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.cancelledOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['cancelledOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-danger" href="{{ route('driver:orders', 'cancelled') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.expiredOrders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['expiredOrdersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-danger" href="{{ route('driver:orders', 'expired') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.Total Branches') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['branchesCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-revenue"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-secondary" href="{{ route('driver:branches') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.Total Employees') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['employeesCount'] }}</h4>
                                            </div>
                                            <div id="crm-conversion-ratio"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-success" href="{{ route('driver:employees') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
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
                                                <p class="text-muted mb-0">{{ __('driver::home.Total Items') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['itemCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-deals"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-warning" href="{{ route('driver:services') }}">{{ __('driver::home.View All') }}<i class="ti ti-arrow-narrow-right ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endDriverHasRole
                </div>
            </div>
            @DriverHasRole(['show orders_general'])
                <div class="col-xl-9">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                {{ __('driver::home.Orders Statistics') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <livewire:driver::home.order.table.all/>
                            </div>
                        </div>
                    </div>
                </div>
                @endDriverHasRole
                @DriverHasRole(['show_employees'])
                <div class="col-xl-3">
                    <div class="card custom-card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">
                                {{ __('driver::home.Top Employees') }}
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
                @endDriverHasRole
        </div>
    </div>
@endsection
