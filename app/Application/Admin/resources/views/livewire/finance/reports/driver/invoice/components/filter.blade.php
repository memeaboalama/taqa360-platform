<div class="col-xl-8">
    <div class="card-body">
        <div class="btn-list">

            {{-- فلتر اسم السائق --}}
            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownDriverName"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.Driver Name') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <div class="row gy-3">
                    <livewire:admin::finance.reports.driver.invoice.components.search />
                </div>
                <div class="dropdown-divider"></div>
            </div>

            @if($driver_name != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeDriverFilter')">
                    {{ __('admin::home.Driver Name') }} | {{ $driver_name }} ({{ $driver_phone }}) <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

            {{-- فلتر اسم المتجر --}}
            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMerchantName"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.Merchant Name') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <div class="row gy-3">
                    <livewire:admin::finance.reports.driver.invoice.components.search-merchant />
                </div>
                <div class="dropdown-divider"></div>
            </div>

            @if($merchant_name != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeMerchantInvoiceFilter')">
                    {{ __('admin::home.Merchant Name') }} | {{ $merchant_name }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

        </div>
    </div>
</div>
