@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " .__('admin::home.Merchants All'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <livewire:admin::merchant.table.filter/>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::merchant.table.all/>
                    </div>
                </div>
                <livewire:admin::merchant.modal.activate/>
                <livewire:admin::merchant.modal.deactivate/>
                <livewire:admin::merchant.modal.reset-password/>
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
