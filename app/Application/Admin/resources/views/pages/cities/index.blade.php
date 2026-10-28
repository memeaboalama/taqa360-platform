@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::lang.cities'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.cities')
                    </div>
                    @AdminHasRole(['create_cities'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createCity">@lang('admin::lang.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::city.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_cities'])
                <livewire:admin::city.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_cities'])
                <livewire:admin::city.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_cities'])
                <livewire:admin::city.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection
