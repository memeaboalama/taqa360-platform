<div class="col-xl-11">
    <style>
        .city-filter-dropdown {
            position: relative;
            display: inline-flex
        }

        .city-filter-dropdown.show {
            z-index: 1060
        }

        .city-filter-menu {
            top: calc(100% + 6px);
            left: 0;
            width: 360px;
            max-width: calc(100vw - 32px);
            padding: 0;
            overflow: hidden
        }

        [dir="rtl"] .city-filter-menu {
            right: 0;
            left: auto
        }

        .city-filter-search,
        .city-filter-actions {
            padding: 12px 14px
        }

        .city-filter-search {
            border-bottom: 1px solid var(--bs-border-color, #e5e7eb)
        }

        .city-filter-options {
            max-height: 250px;
            overflow-y: auto;
            overscroll-behavior: contain;
            padding: 6px
        }

        .city-filter-option {
            display: block;
            width: 100%;
            padding: 8px 10px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: inherit;
            text-align: start;
            cursor: pointer
        }

        .city-filter-option:hover,
        .city-filter-option.is-selected {
            background: rgba(var(--primary-rgb), .1);
            color: rgb(var(--primary-rgb))
        }

        .city-filter-empty,
        .city-filter-load-more {
            display: block;
            width: 100%;
            padding: 9px 10px;
            color: var(--bs-secondary-color, #6c757d);
            text-align: center
        }

        .city-filter-load-more {
            border: 0;
            border-radius: 6px;
            background: transparent;
            cursor: pointer
        }

        .city-filter-load-more:hover {
            background: rgba(var(--primary-rgb), .08);
            color: rgb(var(--primary-rgb))
        }

        .city-filter-actions {
            border-top: 1px solid var(--bs-border-color, #e5e7eb)
        }
    </style>
    <div class="card-body">
        <div class="btn-list">

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
                                    @php($statusValue = $value instanceof \App\Application\Marketplace\Enums\MerchantOrderStatuses ? $value->value : (string) $value)
                                    <option value="{{ $statusValue }}">{{ \App\Application\Marketplace\Enums\MerchantOrderStatuses::tryFrom($statusValue)?->trans() ?? $statusValue }}</option>
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


            <div class="city-filter-dropdown dropdown" wire:ignore.self>
                <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button"
                        id="city-filter-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-controls="city-filter-menu" aria-expanded="false" wire:ignore.self>
                    {{ __('marketplace::home.City') }}
                </button>

                <div class="dropdown-menu city-filter-menu" id="city-filter-menu" wire:ignore.self>
                    <form wire:submit.prevent="filterCity">
                        <div class="city-filter-search">
                            <label for="city-filter-search" class="form-label">{{ __('marketplace::home.City') }}</label>
                            <input type="search" class="form-control" id="city-filter-search"
                                   wire:model.debounce.300ms="citySearch"
                                   placeholder="{{ __('marketplace::home.search_cities') }}"
                                   autocomplete="off" maxlength="100">
                        </div>

                        <div class="city-filter-options" data-lazy-location-options="city" wire:ignore.self>
                            @forelse($cities as $city)
                                <button type="button"
                                        class="city-filter-option {{ (string) $city_id === (string) $city['id'] ? 'is-selected' : '' }}"
                                        wire:click="selectCity({{ $city['id'] }})"
                                        aria-pressed="{{ (string) $city_id === (string) $city['id'] ? 'true' : 'false' }}">
                                    {{ $city['title'] }}
                                </button>
                            @empty
                                <div class="city-filter-empty">{{ __('marketplace::home.no_cities_found') }}</div>
                            @endforelse

                            @if($hasMoreCities)
                                <button type="button" class="city-filter-load-more" data-location-load-more
                                        wire:click="loadMoreCities" wire:loading.attr="disabled"
                                        wire:target="loadMoreCities">
                                    <span wire:loading.remove wire:target="loadMoreCities">{{ __('marketplace::home.load_more_cities') }}</span>
                                    <span wire:loading wire:target="loadMoreCities">
                                        {{ __('marketplace::home.Wait') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2" aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>

                        <div class="city-filter-actions">
                            <button type="submit" class="w-100 btn btn-primary">
                                <span wire:loading.class="d-none" wire:target="filterCity">{{ __('marketplace::home.filter') }}</span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="filterCity">
                                    {{ __('marketplace::home.Wait') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2" aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($city_name != null)
                <a
                    class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                    wire:click="$emit('removeCityFilter')">
                    {{ __('marketplace::home.City') }} | {{ $city_name }} <span><i class="ri-close-circle-line"></i></span>
                </a>
            @endif

            @if($city_id)
                <div wire:key="district-filter-dropdown" class="city-filter-dropdown dropdown" wire:ignore.self>
                    <button class="btn btn-outline-primary rounded-pill btn-wave waves-effect waves-light dropdown-toggle"
                            type="button" id="district-filter-toggle" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" aria-controls="district-filter-menu"
                            aria-expanded="false" wire:ignore.self>
                        {{ __('marketplace::home.District') }}
                    </button>

                    <div class="dropdown-menu city-filter-menu" id="district-filter-menu" wire:ignore.self>
                        <form wire:submit.prevent="filterDistrict">
                            <div class="city-filter-search">
                                <label for="district-filter-search" class="form-label">{{ __('marketplace::home.District') }}</label>
                                <input type="search" class="form-control" id="district-filter-search"
                                       wire:model.debounce.300ms="districtSearch"
                                       placeholder="{{ __('marketplace::home.search_districts') }}"
                                       autocomplete="off" maxlength="100">
                            </div>

                            <div class="city-filter-options" data-lazy-location-options="district" wire:ignore.self>
                                @forelse($districts as $district)
                                    <button type="button"
                                            class="city-filter-option {{ (string) $district_id === (string) $district['id'] ? 'is-selected' : '' }}"
                                            wire:click="$set('district_id', {{ $district['id'] }})"
                                            aria-pressed="{{ (string) $district_id === (string) $district['id'] ? 'true' : 'false' }}">
                                        {{ $district['title'] }}
                                    </button>
                                @empty
                                    <div class="city-filter-empty">{{ __('marketplace::home.no_districts_found') }}</div>
                                @endforelse

                                @if($hasMoreDistricts)
                                    <button type="button" class="city-filter-load-more" data-location-load-more
                                            wire:click="loadMoreDistricts" wire:loading.attr="disabled"
                                            wire:target="loadMoreDistricts">
                                        <span wire:loading.remove wire:target="loadMoreDistricts">{{ __('marketplace::home.load_more_districts') }}</span>
                                        <span wire:loading wire:target="loadMoreDistricts">
                                            {{ __('marketplace::home.Wait') }}
                                            <span class="spinner-border spinner-border-sm align-middle ms-2" aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endif
                            </div>

                            <div class="city-filter-actions">
                                <button type="submit" class="w-100 btn btn-primary">
                                    <span wire:loading.class="d-none" wire:target="filterDistrict">{{ __('marketplace::home.filter') }}</span>
                                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="filterDistrict">
                                        {{ __('marketplace::home.Wait') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2" aria-hidden="true"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($district_name != null)
                    <a
                        class="btn btn-sm btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                        wire:click="$emit('removeDistrictFilter')">
                        {{ __('marketplace::home.District') }} | {{ $district_name }} <span><i class="ri-close-circle-line"></i></span>
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>

@push('js')
    <script>
        (function () {
            var livewireReady = false;

            function isNearListEnd(list) {
                return list.offsetParent !== null
                    && list.scrollTop + list.clientHeight >= list.scrollHeight - 36;
            }

            function loadMoreIfNeeded(list) {
                if (!isNearListEnd(list) || list.dataset.locationLoading === 'true') return;

                var button = list.querySelector('[data-location-load-more]');
                if (!button || button.disabled) return;

                list.dataset.locationLoading = 'true';
                button.click();
            }

            function bindLocationLists() {
                document.querySelectorAll('[data-lazy-location-options]').forEach(function (list) {
                    if (list.dataset.locationScrollBound === 'true') return;

                    list.dataset.locationScrollBound = 'true';
                    list.addEventListener('scroll', function () {
                        loadMoreIfNeeded(list);
                    });
                });
            }

            function startCityLazyLoading() {
                if (livewireReady || !window.Livewire || !Livewire.hook) return;
                livewireReady = true;
                bindLocationLists();

                ['city', 'district'].forEach(function (location) {
                    window.addEventListener('close-' + location + '-filter', function () {
                        var toggle = document.getElementById(location + '-filter-toggle');
                        var dropdown = toggle && window.bootstrap
                            ? bootstrap.Dropdown.getInstance(toggle)
                            : null;
                        if (dropdown) dropdown.hide();
                    });
                });

                Livewire.hook('message.processed', function () {
                    window.requestAnimationFrame(function () {
                        bindLocationLists();
                        document.querySelectorAll('[data-lazy-location-options]').forEach(function (list) {
                            list.dataset.locationLoading = 'false';
                            loadMoreIfNeeded(list);
                        });
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startCityLazyLoading);
            } else {
                startCityLazyLoading();
            }
            document.addEventListener('livewire:load', startCityLazyLoading);
        })();
    </script>
@endpush
