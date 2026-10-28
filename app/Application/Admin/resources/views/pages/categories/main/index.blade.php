@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.cats'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.cats')
                    </div>
                    @AdminHasRole(['create_tags'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createMainCategory">@lang('admin::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::category.main.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_tags'])
                <livewire:admin::category.main.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_tags'])
                <livewire:admin::category.main.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_tags'])
                <livewire:admin::category.main.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection
