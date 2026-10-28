@extends('marketplace::layout.layout')

@section('title',__('marketplace::home.Merchant |') . __('marketplace::home.branches'))
@section('content')
    <!-- Start:: row-3 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title d-flex align-items-center gap-3">
                        <a href="{{ route('marketplace:branches') }}"
                           class="btn btn-sm btn-primary-light" title="{{ __('marketplace::home.Back') }}">
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        @lang('marketplace::home.branches')
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @MerchantHasRole(['edit info_branches'])
                        <button class="btn btn-sm btn-warning" id="btnEditBranch"
                                data-bs-toggle="tooltip" title="@lang('marketplace::home.Edit')">
                            <i class="ri-edit-2-line"></i>
                        </button>
                        <livewire:marketplace::branch.components.status :branch_id="$id"/>
                        @endMerchantHasRole
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3 nav-justified nav-style-1 d-sm-flex d-block" role="tablist">
                        @MerchantHasRole(['edit info_branches'])
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#home1-justified"
                               aria-selected="false">@lang('marketplace::home.Info')</a>
                        </li>
                        @endMerchantHasRole

                        @MerchantHasRole(['choose employees_branches'])
                        <li class="nav-item active">
                            <a class="nav-link " data-bs-toggle="tab" role="tab"
                               href="#service1-justified" aria-selected="true">@lang('marketplace::home.Employees')</a>
                        </li>
                        @endMerchantHasRole

                        @MerchantHasRole(['choose employees_branches'])
                        <li class="nav-item active">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" href="#working-hours-justified"
                               aria-selected="false">@lang('marketplace::home.Working Hours')</a>
                        </li>
                        @endMerchantHasRole
                    </ul>
                    <div class="tab-content">
                        @MerchantHasRole(['edit info_branches'])
                        <div class="tab-pane show active text-muted" id="home1-justified" role="tabpanel">
                            <livewire:marketplace::branch.tabs.info :branch_id="$id"/>
                        </div>
                        @endMerchantHasRole

                        @MerchantHasRole(['choose employees_branches'])
                        <div class="tab-pane text-muted" id="service1-justified" role="tabpanel">
                            <livewire:marketplace::branch.tabs.employee :branch_id="$id"/>
                        </div>
                        @endMerchantHasRole

                        @MerchantHasRole(['edit working days_branches'])
                        <div class="tab-pane text-muted" id="working-hours-justified" role="tabpanel">
                            <livewire:marketplace::branch.tabs.working :branch_id="$id"/>
                        </div>
                        @endMerchantHasRole

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: row-3 -->
    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('btnEditBranch');
            if (btn) {
                btn.addEventListener('click', function () {
                    Livewire.emit('enableEditingInfo');
                });
            }
        });
    </script>
    @endpush
{{--    @push('js')--}}
{{--        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj1qLmrodsYHebNZUltIb20_FrZn7DVs4&callback=initMap"></script>--}}
{{--    @endpush--}}
@endsection
