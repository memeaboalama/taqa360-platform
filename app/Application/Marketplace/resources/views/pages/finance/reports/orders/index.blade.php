@extends('marketplace::layout.layout')
@section('title',__('marketplace::home.Finance Reports'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="orders-report-tab" data-bs-toggle="tab"
                                    data-bs-target="#orders-report-panel" type="button" role="tab"
                                    aria-controls="orders-report-panel" aria-selected="true">
                                {{ __('marketplace::home.orders_report') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="collection-settlement-tab" data-bs-toggle="tab"
                                    data-bs-target="#collection-settlement-panel" type="button" role="tab"
                                    aria-controls="collection-settlement-panel" aria-selected="false">
                                {{ __('marketplace::home.collection_settlement_report') }}
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="orders-report-panel" role="tabpanel"
                         aria-labelledby="orders-report-tab" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between gap-3 p-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <livewire:marketplace::finance.reports.order.components.filter/>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2 ms-auto">
                                <livewire:marketplace::finance.reports.order.components.export/>
                                <livewire:marketplace::finance.reports.order.components.pdf/>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <livewire:marketplace::finance.reports.order.table.all/>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="collection-settlement-panel" role="tabpanel"
                         aria-labelledby="collection-settlement-tab" tabindex="0">
                        <div class="card-body">
                            <livewire:marketplace::finance.reports.order.components.collection-settlement/>
                        </div>
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
