@extends('driver::layout.layout')
@section('title',__('driver::home.Driver |Profile'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                            <div>
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist" wire:ignore>
                                    @DriverHasRole(['show basic info_profile'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="basicInfo-tab" data-bs-toggle="tab"
                                                data-bs-target="#basicInfo-tab-pane" type="button" role="tab"
                                                aria-controls="basicInfo-tab-pane" aria-selected="true"><i
                                                class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('driver::home.Basic Info') }}</button>
                                    </li>
                                    @endDriverHasRole

                                    @DriverHasRole(['show files_profile'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="files-tab" data-bs-toggle="tab"
                                                data-bs-target="#files-tab-pane" type="button" role="tab"
                                                aria-controls="files-tab-pane" aria-selected="false"><i
                                                class="ri-file-3-fill me-1 align-middle d-inline-block"></i>{{ __('driver::home.Files') }}</button>
                                    </li>
                                    @endDriverHasRole

                                    @DriverHasRole(['show iban info_profile'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="iban-tab" data-bs-toggle="tab"
                                                data-bs-target="#iban-tab-pane" type="button" role="tab"
                                                aria-controls="iban-tab-pane" aria-selected="false"><i
                                                class="ri-bank-card-line me-1 align-middle d-inline-block"></i>{{ __('driver::home.iban info') }}</button>
                                    </li>
                                    @endDriverHasRole

                                    @DriverHasRole(['show packages_profile'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="packages-tab" data-bs-toggle="tab"
                                                data-bs-target="#packages-tab-pane" type="button" role="tab"
                                                aria-controls="packages-tab-pane" aria-selected="false"><i
                                                class="bx bxs-badge-dollar me-1 align-middle d-inline-block"></i>{{ __('driver::home.Packages') }}</button>
                                    </li>
                                    @endDriverHasRole
                                </ul>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">
                                @DriverHasRole(['show basic info_profile'])
                                <livewire:driver::profile.tabs.basic />
                                @endDriverHasRole

                                @DriverHasRole(['show files_profile'])
                                <livewire:driver::profile.tabs.files />
                                @endDriverHasRole

                                @DriverHasRole(['show iban info_profile'])
                                <livewire:driver::profile.tabs.iban />
                                @endDriverHasRole

                                @DriverHasRole(['show packages_profile'])
                                <livewire:driver::profile.tabs.packages />
                                <livewire:driver::profile.tabs.package.select />
                                @endDriverHasRole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
