@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::lang.Banners'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.Banners')
                    </div>
                    @AdminHasRole(['create_banners'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createBanner">@lang('admin::lang.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::banner.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_banners'])
                <livewire:admin::banner.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_banners'])
                <livewire:admin::banner.modal.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_banners'])
                <livewire:admin::banner.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection
