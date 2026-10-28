
    <div class="card-body">
        <div class="btn-list">

            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.Code') }}
            </button>

            <div class="dropdown-menu" >
                <form class="px-4 py-3" wire:submit.prevent="filterCode">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="code-filter" class="form-label">{{ __('admin::home.Code') }}</label>
                            <input type="text" class="form-control" id="code-filter" wire:model.defer="code" placeholder="{{ __('admin::home.Code') }}">
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

            @if($code != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeCodeFilter')">
                    {{ __('admin::home.Code') }} | {{ $code }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif


            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.status') }}
            </button>

            <div class="dropdown-menu" >
                <form class="px-4 py-3" wire:submit.prevent="filterStatus">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="status-filter" class="form-label">{{ __('admin::home.status') }}</label>
                            <select class="form-control" id="status-filter" wire:model.defer="status">
                                <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                                @foreach($statuses as $value)
                                    <option value="{{ $value }}">{{ __('marketplace::lang.' . $value->value) }}</option>
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
                    {{ __('admin::home.status') }} | {{ __('marketplace::lang.' . $status) }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.Type') }}
            </button>

            <div class="dropdown-menu" >
                <form class="px-4 py-3" wire:submit.prevent="filterType">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="type-filter" class="form-label">{{ __('admin::home.Type') }}</label>
                            <select class="form-control" id="type-filter" wire:model.defer="type">
                                <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                                @foreach($types as $value)
                                    <option value="{{ $value }}">{{ __('marketplace::lang.' . $value->value) }}</option>
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

            @if($type != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeTypeFilter')">
                    {{ __('admin::home.Type') }} | {{ __('marketplace::lang.' . $type) }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif





            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('admin::home.date') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <form class="px-4 py-3" wire:submit.prevent="filterDate">
                    <div class="row gy-3">
                        <div class="col-xl-4 mt-2">
                            <label for="date_condition-filter" class="form-label">{{ __('admin::home.Condition') }}</label>
                            <select class="form-control"
                                    wire:change="$emit('getBetweenDates')"
                                    id="date_condition-filter"
                                    wire:model.defer="date_condition">
                                <option value="equal">{{ __('admin::home.is equal to') }}</option>
                                <option value="between">{{ __('admin::home.is between') }}</option>
                                <option value="gt">{{ __('admin::home.is on or after') }}</option>
                            </select>
                        </div>

                        <div class="col-xl-6 mt-2">
                            <div>
                                <label for="date-filter" class="form-label">{{ __('admin::home.date') }}</label>
                                <div class="input-group">
                                    <input type="text"
                                           style="cursor: pointer"
                                           class="form-control"
                                           id="date" wire:model.defer="date" placeholder="yyyy-mm-dd">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                </div>
                            </div>
                            <div class="@if(!$to_date_visible) d-none @endif">
                                <label for="to_date-filter" class="form-label">{{ __('admin::home.To') }}</label>
                                <div class="input-group">
                                    <input type="text"
                                           style="cursor: pointer"
                                           class="form-control form-control-sm"
                                           id="to_date-filter" wire:model.defer="to_date" placeholder="yyyy-mm-dd">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                </div>
                            </div>
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

            @if($date != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeDateFilter')">
                    {{ __('admin::home.date') }} | {{ $date }} @if($to_date_visible) -> {{ $to_date }} @endif <span><i class="ri-close-circle-line"></i> </span>
                </a>
            @endif




        </div>
    </div>
