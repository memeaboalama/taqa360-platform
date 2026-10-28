@extends('marketplace::layout.layout')
@section('title',__('marketplace::home.Merchant |'). __('marketplace::home.additions_groups'))

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    @lang('marketplace::home.additions_groups')
                </div>
                @MerchantHasRole(['create_menu addition groups'])
                <div class="prism-toggle">
                    <button class="btn btn-primary-light m-1" data-bs-toggle="modal" data-bs-target="#creatAdditionGroup">@lang('marketplace::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                </div>
                @endMerchantHasRole
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <livewire:marketplace::additiongroup.table.all />
                </div>
            </div>

            @MerchantHasRole(['create_menu addition groups'])
            <livewire:marketplace::additiongroup.modal.create />
            @endMerchantHasRole
            <livewire:marketplace::additiongroup.modal.update />
            @MerchantHasRole(['delete_menu addition groups'])
            <livewire:marketplace::additiongroup.modal.delete />
            @endMerchantHasRole

        </div>
    </div>
</div>

@endsection
