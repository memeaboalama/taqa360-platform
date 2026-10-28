@extends('company::layout.layout')
@section('title',__('company::home.company') . " | ". __('company::home.orders'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="row mb-0 align-items-center">
                    <div class="col-md-8">
                        <livewire:company::order.components.filter/>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end fs-15 gap-2">
                            <livewire:company::order.components.export/>
                            <livewire:company::order.components.pdf/>
                        </div>
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
    @push('js')
        <!-- Date & Time Picker JS -->
        <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{ asset('assets/vendor/js/date&time_pickers.js')}}"></script>
        <script>
            flatpickr("#to_date-filter", {});
        </script>
    @endpush

@endsection
