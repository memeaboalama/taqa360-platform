@extends('marketplace::layout.layout')

@section('title',__('marketplace::home.Merchant |Branches'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('marketplace::home.branches')
                    </div>
                    @MerchantHasRole(['create_branches'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createBranch">@lang('marketplace::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endMerchantHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:marketplace::branch.table.all/>
                    </div>
                </div>
                @MerchantHasRole(['create_branches'])
                <livewire:marketplace::branch.modal.create/>
                @endMerchantHasRole

                @MerchantHasRole(['create_branches'])
                <livewire:marketplace::branch.modal.delete/>
                @endMerchantHasRole
            </div>
        </div>
    </div>
{{--    @push('js')--}}
{{--        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj1qLmrodsYHebNZUltIb20_FrZn7DVs4&callback=initMap"></script>--}}
{{--    @endpush--}}
@endsection
