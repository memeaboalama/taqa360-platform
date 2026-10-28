@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.offers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.offers')
                    </div>
                    <div class="row mb-0">
                        <livewire:admin::offer.table.filter/>
                    </div>
                    @AdminHasRole(['create_coupons'])
                    <div class="prism-toggle" hidden>
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createOffer">{{__('admin::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::offer.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_coupons'])
                <livewire:admin::offer.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_coupons'])
                <livewire:admin::offer.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_coupons'])
                <livewire:admin::offer.modal.delete/>
                @endAdminHasRole

<livewire:admin::offer.modal.enable/>
            </div>
        </div>
    </div>

@endsection
