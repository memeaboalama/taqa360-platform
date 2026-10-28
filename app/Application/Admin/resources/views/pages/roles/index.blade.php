@extends('admin::layout.layout')

@section('title',__('admin::home.admin') . " | " . __('admin::home.roles'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.roles')
                    </div>
                    @AdminHasRole(['create_roles'])
                    <div class="prism-toggle">
                        <a class="btn btn-primary m-1" href="{{ route('admin:roles.create') }}">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></a>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::role.table.all/>
                    </div>
                </div>
            </div>
        </div>
        @AdminHasRole(['delete_roles'])
        <livewire:admin::role.modal.delete/>
        @endAdminHasRole
    </div>
@endsection
