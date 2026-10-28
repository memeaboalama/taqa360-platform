<div class="col-xl-8">
    <div class="card-body">
        <div class="btn-list">

            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.Driver Name') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <div class="row gy-3">
                    <livewire:admin::driver.search.drivers />
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


            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenuOrderStatus"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.Order Status') }}
            </button>

            <div class="dropdown-menu" >
                <form class="px-4 py-3" wire:submit.prevent="filterOrderStatus">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="order-status-filter" class="form-label">{{ __('admin::home.Order Status') }}</label>
                            <select class="form-control" id="order-status-filter" wire:model.defer="order_status">
                                <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                                @foreach($this->order_statuses as $value)
                                    <option value="{{ $value->value }}">{{ $value->trans() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="col-md-12 mt-2 btn btn-primary">
                                    <span class="indicator-label" wire:loading.class="d-none">
                                        {{ __('admin::home.filter') }}
                                    </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                        {{ __('admin::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                        </button>
                    </div>
                </form>
                <div class="dropdown-divider"></div>
            </div>

            @if($order_status != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeOrderStatusFilter')">
                    {{ __('admin::home.Order Status') }} | {{ \App\Application\Marketplace\Enums\MerchantOrderStatuses::tryFrom($order_status)?->trans() ?? $order_status }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

        </div>
    </div>
</div>