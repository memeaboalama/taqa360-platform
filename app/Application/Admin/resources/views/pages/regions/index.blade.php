@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::lang.regions'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.regions')
                    </div>
                    @AdminHasRole(['create_regions'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createRegion">@lang('admin::lang.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::region.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_regions'])
                <livewire:admin::region.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_regions'])
                <livewire:admin::region.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_regions'])
                <livewire:admin::region.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection