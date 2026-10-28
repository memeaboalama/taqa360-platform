@extends('company::layout.layout')

@section('title',__('company::home.company') . " | ". __('company::home.home'))
@section('content')
    @if(session('login_welcome'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="ri-checkbox-circle-line me-2 fs-18"></i>
            <div>{{ session('login_welcome') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Start::row-1 -->
    <div class="row">
        @CompanyHasRole(['show_orders'])
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 pe-0">
                            <p class="mb-2">
                                <span class="fs-16">{{ __('company::home.Completed Orders') }}</span>
                            </p>
                            <p class="mb-2 fs-12">
                                <span class="fs-25 fw-semibold lh-1 vertical-bottom mb-0">{{ $stats['ordersCount'] }}</span>
                            </p>
                            <a href="{{ route('company:orders') }}" class="fs-12 mb-0 text-primary">{{ __('company::home.Show details') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-chevron-left' : 'ti ti-chevron-right' }} ms-1"></i></a>
                        </div>
                        <div class="col-6">
                            <p class="main-card-icon mb-0">
                                <svg class="svg-primary" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/></g><g><g><path d="M12,6c-3.87,0-7,3.13-7,7s3.13,7,7,7s7-3.13,7-7S15.87,6,12,6z M13,14h-2V8h2V14z" opacity=".3"/><rect height="2" width="6" x="9" y="1"/><path d="M19.03,7.39l1.42-1.42c-0.43-0.51-0.9-0.99-1.41-1.41l-1.42,1.42C16.07,4.74,14.12,4,12,4c-4.97,0-9,4.03-9,9 c0,4.97,4.02,9,9,9s9-4.03,9-9C21,10.88,20.26,8.93,19.03,7.39z M12,20c-3.87,0-7-3.13-7-7s3.13-7,7-7s7,3.13,7,7S15.87,20,12,20z"/><rect height="6" width="2" x="11" y="8"/></g></g></svg>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endCompanyHasRole
        @CompanyHasRole(['show_drivers'])
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 pe-0">
                            <p class="mb-2">
                                <span class="fs-16">{{ __('company::home.drivers') }}</span>
                            </p>
                            <p class="mb-2 fs-12">
                                <span class="fs-25 fw-semibold lh-1 vertical-bottom mb-0">{{ $stats['driversCount'] }}</span>
                            </p>
                            <a href="{{ route('company:drivers') }}" class="fs-12 mb-0 text-primary">{{ __('company::home.Show details') }}<i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ti ti-chevron-left' : 'ti ti-chevron-right' }} ms-1"></i></a>
                        </div>


                        <div class="col-6">
                            <p class="main-card-icon mb-0"><svg class="svg-primary" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M19,19c0,0.55-0.45,1-1,1s-1-0.45-1-1v-3H8V5h11V19z" opacity=".3"/><path d="M0,0h24v24H0V0z" fill="none"/><g><path d="M19.5,3.5L18,2l-1.5,1.5L15,2l-1.5,1.5L12,2l-1.5,1.5L9,2L7.5,3.5L6,2v14H3v3c0,1.66,1.34,3,3,3h12c1.66,0,3-1.34,3-3V2 L19.5,3.5z M19,19c0,0.55-0.45,1-1,1s-1-0.45-1-1v-3H8V5h11V19z"/><rect height="2" width="6" x="9" y="7"/><rect height="2" width="2" x="16" y="7"/><rect height="2" width="6" x="9" y="10"/><rect height="2" width="2" x="16" y="10"/></g></svg></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endCompanyHasRole
    </div>
    <!--End::row-1 -->

    @CompanyHasRole(['show_orders'])
    <!-- Start:: row-4 -->
    <div class="row">
        <div class="col-xl-10">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('company::home.Latest Completed Orders') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:company::order.table.all/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endCompanyHasRole
    <!-- End:: row-4 -->
    @push('js')
        <!-- Apex Charts JS -->
        <script src="{{ asset('assets/vendor/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- JSVector Maps JS -->
        <script src="{{ asset('assets/vendor/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>

        <!-- JSVector Maps MapsJS -->
        <script src="{{ asset('assets/vendor/libs/jsvectormap/maps/world-merc.js') }}"></script>

        <!-- Date & Time Picker JS -->
        <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.min.js') }}"></script>

        <!-- Sales-Dashboard JS -->
        <script src="{{ asset('assets/vendor/js/sales-dashboard.js') }}"></script>
    @endpush
@endsection
