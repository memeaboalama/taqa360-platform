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

        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::home.Company Name') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <form class="px-4 py-3" wire:submit.prevent="filterCompany">
                <div class="row gy-3">
                    <div class="col-xl-12 mt-2">
                        <label for="company-filter" class="form-label">{{ __('admin::home.Company Name') }}</label>
                        <select class="form-control" id="company-filter" wire:model.defer="company_id">
                            <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
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

        @if($company_name != null)
            <a
                class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                wire:click="$emit('removeCompanyFilter')">
                {{ __('admin::home.Company Name') }} | {{ $company_name }} <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif



        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenuNetworkStatus"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::home.Network Status') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <form class="px-4 py-3" wire:submit.prevent="filterNetworkStatus">
                <div class="row gy-3">
                    <div class="col-xl-12 mt-2">
                        <label for="network-status-filter" class="form-label">{{ __('admin::home.Network Status') }}</label>
                        <select class="form-control" id="network-status-filter" wire:model.defer="network_status">
                            <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                            @foreach($network_statuses as $value)
                                <option value="{{ $value }}">{{ __('admin::home.' . $value) }}</option>
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

        @if($network_status != null)
            <a
                class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                wire:click="$emit('removeNetworkStatusFilter')">
                {{ __('admin::home.Network Status') }} | {{ __('admin::home.' . $network_status) }} <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif


        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::home.status') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <form class="px-4 py-3" wire:submit.prevent="filterStatus">
                <div class="row gy-3">
                    <div class="col-xl-12 mt-2">
                        <label for="status-filter" class="form-label">{{ __('admin::home.status') }}</label>
                        <select class="form-control" id="status-filter" wire:model.defer="status">
                            <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                            @foreach($statuses as $value)
                                <option value="{{ $value }}">{{ __('admin::home.' . $value) }}</option>
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

        @if($status != null)
            <a
                class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                wire:click="$emit('removeStatusFilter')">
                {{ __('admin::home.status') }} | {{ __('admin::home.' . $status) }} <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif

        <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenuDriverStatus"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::home.driverStatus') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <form class="px-4 py-3" wire:submit.prevent="filterDriverStatus">
                <div class="row gy-3">
                    <div class="col-xl-12 mt-2">
                        <label for="driver-status-filter" class="form-label">{{ __('admin::home.driverStatus') }}</label>
                        <select class="form-control" id="driver-status-filter" wire:model.defer="driver_status">
                            <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                            @foreach($driver_statuses as $value)
                                <option value="{{ $value }}">{{ __('admin::home.' . $value) }}</option>
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

        @if($driver_status != null)
            <a
                class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                wire:click="$emit('removeDriverStatusFilter')">
                {{ __('admin::home.driverStatus') }} | {{ __('admin::home.' . $driver_status) }} <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif

    </div>
</div>
