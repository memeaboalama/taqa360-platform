<div>
    <div class="btn-list d-flex align-items-center flex-wrap gap-2">

            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('marketplace::home.orderCode') }}
            </button>

            <div class="dropdown-menu" >
                <form class="px-4 py-3" wire:submit.prevent="filterCode">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="code-filter" class="form-label">{{ __('marketplace::home.orderCode') }}</label>
                            <input type="text" class="form-control" id="code-filter" wire:model.defer="code" placeholder="{{ __('marketplace::home.enter order code') }}">
                        </div>

                        <button type="submit" class="col-md-12 mt-2 btn btn-primary">
                                    <span class="indicator-label" wire:loading.class="d-none">
                                        {{ __('marketplace::home.filter') }}
                                    </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                        {{ __('marketplace::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
                    {{ __('marketplace::home.orderCode') }} | {{ $code }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('marketplace::home.status') }}
            </button>

            <div class="dropdown-menu" >
                <form class="px-4 py-3" wire:submit.prevent="filterStatus">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="status-filter" class="form-label">{{ __('marketplace::home.status') }}</label>
                            <select class="form-control" id="status-filter" wire:model.defer="status">
                                <option selected value="">-- {{ __('marketplace::home.Select') }} --</option>
                                @foreach($this->statuses as $value)
                                    <option value="{{ $value }}">{{  $value->trans() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="col-md-12 mt-2 btn btn-primary">
                                    <span class="indicator-label" wire:loading.class="d-none">
                                        {{ __('marketplace::home.filter') }}
                                    </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                        {{ __('marketplace::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
                    {{ __('marketplace::home.status') }} | {{ $status }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif



            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('marketplace::home.date') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <form class="px-4 py-3" wire:submit.prevent="filterDate">
                    <div class="row gy-3">
                        <div class="col-xl-4 mt-2">
                            <label for="date_condition-filter" class="form-label">{{ __('marketplace::home.Condition') }}</label>
                            <select class="form-control"
                                    wire:change="$emit('getBetweenDates')"
                                    id="date_condition-filter"
                                    wire:model.defer="date_condition">
                                <option value="equal">{{ __('marketplace::home.is equal to') }}</option>
                                <option value="between">{{ __('marketplace::home.is between') }}</option>
                                <option value="gt">{{ __('marketplace::home.is on or after') }}</option>
                            </select>
                        </div>

                        <div class="col-xl-6 mt-2">
                            <div>
                                <label for="date-filter" class="form-label">{{ __('marketplace::home.date') }}</label>
                                <div class="input-group">
                                    <input type="text"
                                           style="cursor: pointer"
                                           class="form-control"
                                           id="date" wire:model.defer="date" placeholder="yyyy-mm-dd">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                </div>
                            </div>
                            <div class="@if(!$to_date_visible) d-none @endif">
                                <label for="to_date-filter" class="form-label"> {{ __('marketplace::home.To') }}</label>
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
                                        {{ __('marketplace::home.filter') }}
                                    </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                        {{ __('marketplace::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
                    {{ __('marketplace::home.date') }} | {{ $date }} @if($to_date_visible) -> {{ $to_date }} @endif <span><i class="ri-close-circle-line"></i> </span>
                </a>
            @endif


            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('marketplace::home.City') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <form class="px-4 py-3" wire:submit.prevent="filterCity">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="city-filter" class="form-label">{{ __('marketplace::home.City') }}</label>
                            <select class="form-control" id="city-filter" wire:model.defer="city_id">
                                <option selected value="">-- {{ __('marketplace::home.Select') }} --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="col-md-12 mt-2 btn btn-primary">
                                    <span class="indicator-label" wire:loading.class="d-none">
                                        {{ __('marketplace::home.filter') }}
                                    </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                        {{ __('marketplace::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                        </button>
                    </div>
                </form>
                <div class="dropdown-divider"></div>
            </div>

            @if($city_name != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeCityFilter')">
                    {{ __('marketplace::home.City') }} | {{ $city_name }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

            <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('marketplace::home.District') }}
            </button>

            <div class="dropdown-menu" wire:ignore.self>
                <form class="px-4 py-3" wire:submit.prevent="filterDistrict">
                    <div class="row gy-3">
                        <div class="col-xl-12 mt-2">
                            <label for="district-filter" class="form-label">{{ __('marketplace::home.District') }}</label>
                            <select class="form-control" id="district-filter" wire:model.defer="district_id">
                                <option selected value="">-- {{ __('marketplace::home.Select') }} --</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="col-md-12 mt-2 btn btn-primary">
                                    <span class="indicator-label" wire:loading.class="d-none">
                                        {{ __('marketplace::home.filter') }}
                                    </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                        {{ __('marketplace::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                        </button>
                    </div>
                </form>
                <div class="dropdown-divider"></div>
            </div>

            @if($district_name != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeDistrictFilter')">
                    {{ __('marketplace::home.District') }} | {{ $district_name }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif
        </div>
</div>
