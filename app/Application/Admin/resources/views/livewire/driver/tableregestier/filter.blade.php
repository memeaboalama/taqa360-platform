<div class="card-body">
    <div class="btn-list">

    


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
                                <option value="{{ $value  }}">{{ $value }}</option>
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
                {{ __('admin::home.status') }} | {{ $status }} <span><i class="ri-close-circle-line"></i></span>
            </a>
        @endif

    </div>
</div>
