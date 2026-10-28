@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Invoice Delivery'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="row mb-0">
                    <livewire:admin::finance.reports.driver.invoice.components.filter/>
                </div>

                <div class="card-body ">
                    <div class="table-responsive">
                        <livewire:admin::finance.reports.driver.invoice.table.all/>
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
