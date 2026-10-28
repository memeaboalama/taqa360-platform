@extends('marketplace::layout.layout')
@section('title',__('marketplace::home.Finance Reports'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="d-flex align-items-center justify-content-between gap-3 p-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <livewire:marketplace::finance.reports.order.components.filter/>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2 ms-auto">
                        <livewire:marketplace::finance.reports.order.components.export/>
                        <livewire:marketplace::finance.reports.order.components.pdf/>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="table-responsive">
                        <livewire:marketplace::finance.reports.order.table.all/>
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
