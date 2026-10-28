@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.operatingCompanies'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.operatingCompanies') }}
                    </div>
                    @AdminHasRole(['create_operating companies'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createCompany">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::company.table.all/>
                    </div>
                </div>
                @AdminHasRole(['create_operating companies'])
                <livewire:admin::company.modal.create/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection
