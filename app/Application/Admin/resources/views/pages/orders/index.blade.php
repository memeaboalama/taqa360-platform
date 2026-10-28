@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.orders'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="row mb-2">
                    <livewire:admin::order.table.filter/>
                    
             </div>

                <div class="card-body ">
                    <div class="table-responsive">
                        <livewire:admin::order.table.all/>
                    </div>
                </div>
                <livewire:admin::order.modal.cancel/>
                <livewire:admin::order.modal.assign/>
                <livewire:admin::order.modal.elmerrors/>
                <livewire:admin::order.modal.history/>
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
