@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.MarketPlace Categories'))


@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.MarketPlace Categories')
                    </div>
                    @AdminHasRole(['create_categories'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createCategory">@lang('admin::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::category.marketplace.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_categories'])
                <livewire:admin::category.marketplace.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_categories'])
                <livewire:admin::category.marketplace.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_categories'])
                <livewire:admin::category.marketplace.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>



@endsection
