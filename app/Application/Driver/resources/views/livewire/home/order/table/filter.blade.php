<div class="driver-home-order-filters mb-3">
    <style>
        .driver-home-location-filter {
            position: relative;
            display: inline-flex;
            width: 100%;
        }

        .driver-home-location-filter.show {
            z-index: 1060;
        }

        .driver-home-location-menu {
            top: calc(100% + 6px);
            width: 320px;
            max-width: calc(100vw - 32px);
            padding: 0;
            overflow: hidden;
        }

        .driver-home-location-search,
        .driver-home-location-actions {
            padding: 10px 12px;
        }

        .driver-home-location-search {
            border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
        }

        .driver-home-location-options {
            max-height: 220px;
            overflow-y: auto;
            overscroll-behavior: contain;
            padding: 6px;
        }

        .driver-home-location-option,
        .driver-home-location-load-more {
            display: block;
            width: 100%;
            padding: 8px 10px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: inherit;
            text-align: start;
        }

        .driver-home-location-option,
        .driver-home-location-load-more {
            cursor: pointer;
        }

        .driver-home-location-option:hover,
        .driver-home-location-option.is-selected,
        .driver-home-location-load-more:hover {
            background: rgba(var(--primary-rgb), .1);
            color: rgb(var(--primary-rgb));
        }

        .driver-home-location-empty {
            padding: 9px 10px;
            color: var(--bs-secondary-color, #6c757d);
            text-align: center;
        }

        .driver-home-location-load-more {
            color: var(--bs-secondary-color, #6c757d);
            text-align: center;
        }

        .driver-home-location-actions {
            border-top: 1px solid var(--bs-border-color, #e5e7eb);
        }
    </style>

    <div class="row g-2 align-items-end">
        <div class="col-12 col-md-6 col-xl-3">
            <label for="driver-order-code-filter" class="form-label">{{ __('driver::home.orderCode') }}</label>
            <input id="driver-order-code-filter" type="search" class="form-control"
                   wire:model.defer="code" placeholder="{{ __('driver::home.enter order code') }}"
                   maxlength="100">
        </div>

        <div class="col-12 col-md-6 col-xl-2">
            <label for="driver-order-status-filter" class="form-label">{{ __('driver::home.status') }}</label>
            <select id="driver-order-status-filter" class="form-select" wire:model.defer="status">
                <option value="">{{ __('driver::home.all_order_statuses') }}</option>
                @foreach(\App\Application\Driver\Enums\DriverOrderStatuses::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}">{{ $statusOption->trans() }}</option>
                @endforeach
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-6 col-xl-2">
            <label for="driver-order-date-from" class="form-label">{{ __('driver::home.order_date_from') }}</label>
            <input id="driver-order-date-from" type="date" class="form-control" wire:model.defer="dateFrom">
        </div>

        <div class="col-6 col-xl-2">
            <label for="driver-order-date-to" class="form-label">{{ __('driver::home.order_date_to') }}</label>
            <input id="driver-order-date-to" type="date" class="form-control" wire:model.defer="dateTo">
            @error('dateTo')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-6 col-xl-1">
            <div class="driver-home-location-filter dropdown" wire:ignore.self>
                <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                        id="driver-city-filter-toggle" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false" wire:ignore.self>
                    {{ $cityName ?: __('driver::home.City') }}
                </button>
                <div class="dropdown-menu driver-home-location-menu" wire:ignore.self>
                    <div class="driver-home-location-search">
                        <label for="driver-city-filter-search" class="form-label">{{ __('driver::home.City') }}</label>
                        <input id="driver-city-filter-search" type="search" class="form-control"
                               wire:model.debounce.300ms="citySearch"
                               placeholder="{{ __('driver::home.search_cities') }}"
                               autocomplete="off" maxlength="100">
                    </div>

                    <div class="driver-home-location-options" data-lazy-location-options="driver-city" wire:ignore.self>
                        @forelse($cities as $city)
                            <button type="button"
                                    class="driver-home-location-option {{ (string) $cityId === (string) $city['id'] ? 'is-selected' : '' }}"
                                    wire:click="selectCity({{ $city['id'] }})"
                                    aria-pressed="{{ (string) $cityId === (string) $city['id'] ? 'true' : 'false' }}">
                                {{ $city['title'] }}
                            </button>
                        @empty
                            <div class="driver-home-location-empty">{{ __('driver::home.no_cities_found') }}</div>
                        @endforelse

                        @if($hasMoreCities)
                            <button type="button" class="driver-home-location-load-more" data-location-load-more
                                    wire:click="loadMoreCities" wire:loading.attr="disabled"
                                    wire:target="loadMoreCities">
                                <span wire:loading.remove wire:target="loadMoreCities">{{ __('driver::home.load_more_cities') }}</span>
                                <span wire:loading wire:target="loadMoreCities">
                                    {{ __('driver::home.Wait') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2" aria-hidden="true"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($cityId)
            <div class="col-6 col-xl-1">
                <div class="driver-home-location-filter dropdown" wire:ignore.self>
                    <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                            id="driver-district-filter-toggle" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" aria-expanded="false" wire:ignore.self>
                        {{ $districtName ?: __('driver::home.District') }}
                    </button>
                    <div class="dropdown-menu driver-home-location-menu" wire:ignore.self>
                        <div class="driver-home-location-search">
                            <label for="driver-district-filter-search" class="form-label">{{ __('driver::home.District') }}</label>
                            <input id="driver-district-filter-search" type="search" class="form-control"
                                   wire:model.debounce.300ms="districtSearch"
                                   placeholder="{{ __('driver::home.search_districts') }}"
                                   autocomplete="off" maxlength="100">
                        </div>

                        <div class="driver-home-location-options" data-lazy-location-options="driver-district" wire:ignore.self>
                            @forelse($districts as $district)
                                <button type="button"
                                        class="driver-home-location-option {{ (string) $districtId === (string) $district['id'] ? 'is-selected' : '' }}"
                                        wire:click="selectDistrict({{ $district['id'] }})"
                                        aria-pressed="{{ (string) $districtId === (string) $district['id'] ? 'true' : 'false' }}">
                                    {{ $district['title'] }}
                                </button>
                            @empty
                                <div class="driver-home-location-empty">{{ __('driver::home.no_districts_found') }}</div>
                            @endforelse

                            @if($hasMoreDistricts)
                                <button type="button" class="driver-home-location-load-more" data-location-load-more
                                        wire:click="loadMoreDistricts" wire:loading.attr="disabled"
                                        wire:target="loadMoreDistricts">
                                    <span wire:loading.remove wire:target="loadMoreDistricts">{{ __('driver::home.load_more_districts') }}</span>
                                    <span wire:loading wire:target="loadMoreDistricts">
                                        {{ __('driver::home.Wait') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2" aria-hidden="true"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12 col-xl-1 d-flex gap-2">
            <button type="button" class="btn btn-primary flex-fill" wire:click="applyFilters"
                    wire:loading.attr="disabled" wire:target="applyFilters">
                <span wire:loading.remove wire:target="applyFilters">{{ __('driver::home.filter') }}</span>
                <span wire:loading wire:target="applyFilters">
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                </span>
            </button>
            <button type="button" class="btn btn-light" wire:click="clearFilters"
                    title="{{ __('driver::home.clear_filters') }}" aria-label="{{ __('driver::home.clear_filters') }}">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>
</div>

@push('js')
    <script>
        (function () {
            var livewireReady = false;

            function loadMoreIfNeeded(list) {
                if (list.offsetParent === null
                    || list.scrollTop + list.clientHeight < list.scrollHeight - 36
                    || list.dataset.locationLoading === 'true') {
                    return;
                }

                var button = list.querySelector('[data-location-load-more]');
                if (!button || button.disabled) return;

                list.dataset.locationLoading = 'true';
                button.click();
            }

            function bindLocationLists() {
                document.querySelectorAll('[data-lazy-location-options^="driver-"]').forEach(function (list) {
                    if (list.dataset.locationScrollBound === 'true') return;

                    list.dataset.locationScrollBound = 'true';
                    list.addEventListener('scroll', function () {
                        loadMoreIfNeeded(list);
                    });
                });
            }

            function startLocationLazyLoading() {
                if (livewireReady || !window.Livewire || !Livewire.hook) return;
                livewireReady = true;
                bindLocationLists();

                Livewire.hook('message.processed', function () {
                    window.requestAnimationFrame(function () {
                        bindLocationLists();
                        document.querySelectorAll('[data-lazy-location-options^="driver-"]').forEach(function (list) {
                            list.dataset.locationLoading = 'false';
                            loadMoreIfNeeded(list);
                        });
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startLocationLazyLoading);
            } else {
                startLocationLazyLoading();
            }
            document.addEventListener('livewire:load', startLocationLazyLoading);
        })();
    </script>
@endpush
