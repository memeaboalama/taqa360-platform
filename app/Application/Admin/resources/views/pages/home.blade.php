@extends('admin::layout.layout')

@section('title',__('admin::home.admin') . " | " . __('admin::home.home'))
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
            <div class="col-xl-7">
                @AdminHasRole(['show homepage_general'])
                    <div class="row">

                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-primary">
                                            <i class="bx bx-restaurant fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('admin::home.Total Merchants') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['merchantsCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-primary" href="{{ url('/admin/merchants') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="col-xxl-6 col-lg-6 col-md-6">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                        <span class="avatar avatar-md avatar-rounded bg-primary">
                                            <i class="bx bx-restaurant fs-16"></i>
                                        </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">{{ __('admin::home.Total Pending Merchants') }}</p>
                                                    <h4 class="fw-semibold mt-1">{{ $stats['pendingMerchantsCount'] }}</h4>
                                                </div>
                                                <div id="crm-total-customers"></div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <div>
                                                    <a class="text-primary" href="{{ url('/admin/merchants/pending') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-lg-6 col-md-6">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                            <i class="bx bxs-user fs-16"></i>
                                        </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">{{ __('admin::home.Total Customers') }}</p>
                                                    <h4 class="fw-semibold mt-1">{{ $stats['customersCount'] }}</h4>
                                                </div>
                                                <div id="crm-total-customers"></div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <div>
                                                    <a class="text-primary" href="{{ url('admin/customers') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                            <i class="bx bxs-shopping-bag fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('admin::home.Total Orders') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['ordersCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-customers"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-primary" href="{{ url('admin/merchants/orders/get') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-lg-6 col-md-6">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                        <span class="avatar avatar-md avatar-rounded bg-primary">
                                            <i class="bx bxs-truck fs-16"></i>
                                        </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">{{ __('admin::home.Total Companies') }}</p>
                                                    <h4 class="fw-semibold mt-1">{{ $stats['companiesCount'] }}</h4>
                                                </div>
                                                <div id="crm-total-customers"></div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <div>
                                                    <a class="text-primary" href="{{ url('/admin/companies') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-secondary">
                                            <i class="bx bxs-truck fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('admin::home.Total Drivers') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['driversCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-revenue"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-secondary" href="{{ url('/admin/drivers') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="col-xxl-6 col-lg-6 col-md-6">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                        <span class="avatar avatar-md avatar-rounded bg-primary">
                                            <i class="bx bxs-truck fs-16"></i>
                                        </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">{{ __('admin::home.Total Pending Drivers') }}</p>
                                                    <h4 class="fw-semibold mt-1">{{ $stats['pendingDriversCount'] }}</h4>
                                                </div>
                                                <div id="crm-total-revenue"></div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <div>
                                                    <a class="text-secondary" href="{{ url('/admin/drivers/pending') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                                            <i class="bx bx-wifi fs-16"></i>
                                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('admin::home.Total Online Drivers') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['onlineDriversCount'] }}</h4>
                                            </div>
                                            <div id="crm-conversion-ratio"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-success" href="{{ url('/admin/drivers/tracking') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                        <span class="avatar avatar-md avatar-rounded bg-success">
                                            <i class="ti ti-currency-dollar fs-16"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('admin::home.Total Sales') }} ({{ __('admin::home.SAR') }})</p>
                                                <h4 class="fw-semibold mt-1">{{ number_format($stats['salesCount'], 2) }} </h4>
                                            </div>
                                            <div id="crm-total-deals"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-warning" href="{{ url('/admin/merchants/orders/get') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-top justify-content-between">
                                    <div>
                                                        <span class="avatar avatar-md avatar-rounded bg-warning">
                                                            <i class="ti ti-message fs-16"></i>
                                                        </span>
                                    </div>
                                    <div class="flex-fill ms-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <p class="text-muted mb-0">{{ __('admin::home.Total Messages') }}</p>
                                                <h4 class="fw-semibold mt-1">{{ $stats['messagesCount'] }}</h4>
                                            </div>
                                            <div id="crm-total-deals"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <div>
                                                <a class="text-warning" href="{{ url('/admin/messages') }}">{{ __('admin::home.View All') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-arrow-narrow-left' : 'ti ti-arrow-narrow-right' }} ms-2 fw-semibold d-inline-block"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endAdminHasRole
                <div class="row p-3"></div>
                @AdminHasRole(['show_orders'])
                <div class="row p-3">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                {{ __('admin::home.Orders Statistics') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <livewire:admin::home.order.merchant.all/>
                            </div>
                        </div>
                    </div>
                </div>
                @endAdminHasRole
            </div>
            @AdminHasRole(['show homepage_general'])
            <div class="col-xl-5">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xxl-12 col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title">
                                    {{ __('admin::home.Orders Statistics') }}
                                </div>
                            </div>
                            <div class="card-body">
                                <input id="ordersByMonth" type="hidden" value="{{$ordersByMonth}}">
                                <input id="salesByMonth" type="hidden" value="{{$salesByMonth}}">
                                <div id="crm-revenue-analytics"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                {{ __('admin::home.Best Traders') }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('admin::home.Merchant') }}</th>
                                        <th>{{ __('admin::home.totalServices') }} ({{ __('admin::home.SAR') }})</th>
                                        <th>{{ __('admin::home.totalItemsQty') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($bestSellers as $merchant)
                                        <tr>
                                            <td>{{ $merchant?->id }}</td>
                                            <td>
                                                {{ $merchant?->company_name }}
                                            </td>
                                            <td><div class="fw-semibold fs-15">{{ number_format($merchant?->totalPrice, 2) }}</div></td>
                                            <td><p class="fw-semibold mb-0">{{ $merchant?->totalItemsQty }}</p></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endAdminHasRole
        </div>
    </div>
    @push('js')
        <script type="text/javascript">
            const textGraphTranslated = '<?php echo __('admin::home.Revenue Analytics with sales & profit (SAR)') ?>'
        </script>
        <!-- Apex Charts JS -->
        <script src="{{ asset('assets/vendor/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Chartjs Chart JS -->
        <script src="{{ asset('assets/vendor/libs/chart.js/chart.min.js')}}"></script>
        <!-- CRM-Dashboard -->
        <script src="{{ asset('assets/vendor/js/crm-dashboard.js')}}"></script>
    @endpush
@endsection
