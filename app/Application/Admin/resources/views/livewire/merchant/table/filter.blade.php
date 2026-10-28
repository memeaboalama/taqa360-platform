<div>
    <div class="col-xl-12">
        <div class="card-body">
            <div class="btn-list">

                <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('admin::home.Merchant Name') }}
                </button>

                <div class="dropdown-menu" wire:ignore.self>
                    <div class="row gy-3">
                        <livewire:admin::order.search.merchants />
                    </div>
                    <div class="dropdown-divider"></div>
                </div>

                @if($merchant_company_name != null)
                    <a
                        class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                        wire:click="$emit('removeMerchantFilter')">
                        {{ __('admin::home.Merchant Name') }} | {{ $merchant_company_name }} <span><i class="ri-close-circle-line"></i></span>
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
                                        <option value="{{ $value->value }}">{{ $value instanceof \App\Support\Enum\StatusEnum ? $value->label() : $value->trans() }}</option>
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
                        {{ __('admin::home.status') }} | {{ \App\Support\Enum\StatusEnum::tryFrom($status)?->label() ?? $status }} <span><i class="ri-close-circle-line"></i></span>
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
                                    <label for="to_date-filter" class="form-label">To</label>
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

                <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('admin::home.Region') }}
                </button>

                <div class="dropdown-menu" wire:ignore.self>
                    <form class="px-4 py-3" wire:submit.prevent="filterRegion">
                        <div class="row gy-3">
                            <div class="col-xl-12 mt-2">
                                <label for="district-filter" class="form-label">{{ __('admin::home.Region') }}</label>
                                <select class="form-control" id="region-filter" wire:model.defer="region_id">
                                    <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->title }}</option>
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

                @if($region_name != null)
                    <a
                        class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                        wire:click="$emit('removeRegionFilter')">
                        {{ __('admin::home.Region Name') }} | {{ $region_name }} <span><i class="ri-close-circle-line"></i></span>
                    </a>
                @endif

                <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('admin::home.City') }}
                </button>

                <div class="dropdown-menu" wire:ignore.self>
                    <form class="px-4 py-3" wire:submit.prevent="filterCity">
                        <div class="row gy-3">
                            <div class="col-xl-12 mt-2">
                                <label for="city-filter" class="form-label">{{ __('admin::home.City Name') }}</label>
                                <select class="form-control" id="city-filter" wire:model.defer="city_id">
                                    <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->title }}</option>
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

                @if($city_name != null)
                    <a
                        class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                        wire:click="$emit('removeCityFilter')">
                        {{ __('admin::home.City Name') }} | {{ $city_name }} <span><i class="ri-close-circle-line"></i></span>
                    </a>
                @endif

                {{-- <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('admin::home.District') }}
                </button>

                <div class="dropdown-menu" wire:ignore.self>
                    <form class="px-4 py-3" wire:submit.prevent="filterDistrict">
                        <div class="row gy-3">
                            <div class="col-xl-12 mt-2">
                                <label for="district-filter" class="form-label">{{ __('admin::home.District') }}</label>
                                <select class="form-control" id="district-filter" wire:model.defer="district_id">
                                    <option selected value="">-- {{ __('admin::home.Select') }} --</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->title }}</option>
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

                @if($district_name != null)
                    <a
                        class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                        wire:click="$emit('removeDistrictFilter')">
                        {{ __('admin::home.District Name') }} | {{ $district_name }} <span><i class="ri-close-circle-line"></i></span>
                    </a>
                @endif --}}



            </div>
        </div>
    </div>
</div>
