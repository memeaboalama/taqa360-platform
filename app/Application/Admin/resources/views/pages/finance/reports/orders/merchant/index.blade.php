@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Merchant Order Report'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="row mb-0 align-items-center">
                    <div class="col-md-8">
                        <livewire:admin::finance.reports.order.merchant.components.filter/>
                    </div>
                    @AdminHasRole(['export merchant order report_reports'])
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end fs-15">
                            <livewire:admin::finance.reports.order.merchant.components.export/>
                        </div>
                    </div>
                    @endAdminHasRole
                </div>

                <div class="card-body ">
                    <div class="table-responsive">
                        <livewire:admin::finance.reports.order.merchant.table.all/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <!-- Date & Time Picker JS -->
        <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{ asset('assets/vendor/js/date&time_pickers.js')}}"></script>
        <script>
            flatpickr("#to_date-filter", {});
        </script>
    @endpush
@endsection
