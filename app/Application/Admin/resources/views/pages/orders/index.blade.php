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
                 <livewire:admin::order.table.all/>
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

            // تحديث تلقائي لجدول الطلبات كل 5 ثوانٍ
            (function () {
                var adminOrdersRefreshTimer = null;
                function startAdminOrdersRefresh() {
                    if (adminOrdersRefreshTimer) clearInterval(adminOrdersRefreshTimer);
                    adminOrdersRefreshTimer = setInterval(function () {
                        Livewire.emit('refreshDatatable');
                    }, 5000);
                }
                if (window.Livewire) {
                    startAdminOrdersRefresh();
                } else {
                    document.addEventListener('livewire:load', startAdminOrdersRefresh);
                }
            })();
        </script>
    @endpush
@endsection
