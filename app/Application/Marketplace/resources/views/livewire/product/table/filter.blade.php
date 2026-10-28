<div class="card-body">
    <div class="btn-list">

        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle"
                type="button" id="productDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::main.lang.product') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <div class="row gy-3">
                <livewire:marketplace::product.search.search searchType="item" :key="'product-search'" />
            </div>
            <div class="dropdown-divider"></div>
        </div>

        @if($item_name != null)
            <a class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
               wire:click="$emit('removeItemFilter')">
                {{ __('admin::main.lang.product') }} | {{ $item_name }}
                <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif

        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle"
                type="button" id="merchantDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::main.fields.merchant') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <div class="row gy-3">
                <livewire:marketplace::product.search.search searchType="merchant" :key="'merchant-search'" />
            </div>
            <div class="dropdown-divider"></div>
        </div>

        @if($merchant_name != null)
            <a class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
               wire:click="$emit('removeMerchantFilter')">
                {{ __('admin::main.fields.merchant') }} | {{ $merchant_name }}
                <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif

        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle"
                type="button" id="statusDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::main.fields.status') }}
        </button>

        <div class="dropdown-menu">
            <form class="px-4 py-3" wire:submit.prevent="filterStatus">
                <div class="row gy-3">
                    <div class="col-xl-12 mt-2">
                        <label for="status-filter" class="form-label">{{ __('admin::main.fields.status') }}</label>
                        <select class="form-control" id="status-filter" wire:model.defer="status">
                            <option value="">-- {{ __('admin::main.actions.select') }} --</option>
                            @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption->value }}" @if($status == $statusOption->value) selected @endif>
                                    {{ $statusOption->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="col-md-12 mt-2 btn btn-primary">
                        <span class="indicator-label" wire:loading.class="d-none">
                            {{ __('admin::main.actions.filter') }}
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                            {{ __('admin::main.actions.wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        @if($status != null)
            <a class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                wire:click="$emit('removeStatusFilter')">
                {{ __('admin::main.fields.status') }} | {{ __("admin::main.status.$status") }}
                <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif

    </div>
</div>
