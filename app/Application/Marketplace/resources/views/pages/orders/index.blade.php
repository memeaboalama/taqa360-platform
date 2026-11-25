@extends('marketplace::layout.layout')
@section('title',__('marketplace::home.Merchant |Orders'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="row mb-0">
                    <livewire:marketplace::order.table.filter/>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:marketplace::order.table.all/>
                    </div>
                </div>
                <livewire:marketplace::order.modal.cancel/>
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
                var merchantOrdersRefreshTimer = null;
                function startMerchantOrdersRefresh() {
                    if (merchantOrdersRefreshTimer) clearInterval(merchantOrdersRefreshTimer);
                    merchantOrdersRefreshTimer = setInterval(function () {
                        Livewire.emit('refreshDatatable');
                    }, 5000);
                }
                if (window.Livewire) {
                    startMerchantOrdersRefresh();
                } else {
                    document.addEventListener('livewire:load', startMerchantOrdersRefresh);
                }
            })();
        </script>
    @endpush
@endsection
