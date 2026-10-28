@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | "  .__('admin::home.General Settings'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                            <div>
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist" wire:ignore>
                                    @AdminHasRole(['show profile_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#profile-tab-pane" type="button" role="tab"
                                                aria-controls="profile-tab-pane" aria-selected="true"><i
                                                class="ri-user-2-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.Update Profile') }}</button>
                                    </li>
                                    @endAdminHasRole

                                    @AdminHasRole(['show login/ register page content_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="basicUserInfo-tab" data-bs-toggle="tab"
                                                data-bs-target="#basicUserInfo-tab-pane" type="button" role="tab"
                                                aria-controls="basicUserInfo-tab-pane" aria-selected="true"><i
                                                class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.User Page Content') }}</button>
                                    </li>
                                    @endAdminHasRole

                                    @AdminHasRole(['show login/ register page content_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="basicInfo-tab" data-bs-toggle="tab"
                                                data-bs-target="#basicInfo-tab-pane" type="button" role="tab"
                                                aria-controls="basicInfo-tab-pane" aria-selected="true"><i
                                                class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.Login/Register Page Content') }}</button>
                                    </li>
                                    @endAdminHasRole



                                    @AdminHasRole(['show search capacity_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="searchCapacity-tab" data-bs-toggle="tab"
                                                data-bs-target="#searchCapacity-tab-pane" type="button" role="tab"
                                                aria-controls="searchCapacity-tab-pane" aria-selected="true"><i
                                                class="ri-search-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.Search Capacity') }}</button>
                                    </li>
                                    @endAdminHasRole

                                    @AdminHasRole(['show delivery fees_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="DeliveryFees-tab" data-bs-toggle="tab"
                                                data-bs-target="#DeliveryFees-tab-pane" type="button" role="tab"
                                                aria-controls="DeliveryFees-tab-pane" aria-selected="true"><i
                                                class="ri-exchange-dollar-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.Delivery Fees') }}</button>
                                    </li>
                                    @endAdminHasRole

                                    @AdminHasRole(['show delivery fees_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="OrdersSettings-tab" data-bs-toggle="tab"
                                                data-bs-target="#OrdersSettings-tab-pane" type="button" role="tab"
                                                aria-controls="OrdersSettings-tab-pane" aria-selected="true"><i
                                                class="ri-exchange-dollar-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.Orders Settings') }}</button>
                                    </li>
                                    @endAdminHasRole
                                    @AdminHasRole(['show delivery fees_settings'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="OrdersSettings-tab" data-bs-toggle="tab"
                                                data-bs-target="#PaymentSettings-tab-pane" type="button" role="tab"
                                                aria-controls="PaymentSettings-tab-pane" aria-selected="true"><i
                                                class="ri-exchange-dollar-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.Payment Methods Commission') }}</button>
                                    </li>
                                    @endAdminHasRole
                                </ul>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">
                                @AdminHasRole(['show profile_settings'])
                                <livewire:admin::settings.tabs.profile />
                                @endAdminHasRole

                                @AdminHasRole(['show login/ register page content_settings'])
                                <livewire:admin::settings.tabs.content.auth />
                                @endAdminHasRole
                                @AdminHasRole(['show login/ register page content_settings'])
                                <livewire:admin::settings.tabs.content-user.auth />
                                @endAdminHasRole

                                @AdminHasRole(['show search capacity_settings'])
                                <livewire:admin::settings.tabs.capacity />
                                @endAdminHasRole

                                @AdminHasRole(['show delivery fees_settings'])
                                <livewire:admin::settings.tabs.delivery />
                                @endAdminHasRole

                                @AdminHasRole(['show delivery fees_settings'])
                                <livewire:admin::settings.tabs.orders />
                                @endAdminHasRole

                                @AdminHasRole(['show delivery fees_settings'])
                                <livewire:admin::settings.tabs.payment />
                                @endAdminHasRole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
