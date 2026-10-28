@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Specifications'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Specifications')
                    </div>
                    @AdminHasRole(['create_specifications'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createSpecification">@lang('admin::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::specification.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_specifications'])
                <livewire:admin::specification.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_specifications'])
                <livewire:admin::specification.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_specifications'])
                <livewire:admin::specification.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection
