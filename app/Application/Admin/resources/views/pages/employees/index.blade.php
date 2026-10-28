@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.employees'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.Employees') }}
                    </div>
                    @AdminHasRole(['create_employees'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createEmployee">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::employee.table.all/>
                    </div>
                </div>
                @AdminHasRole(['create_employees'])
                <livewire:admin::employee.modal.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_employees'])
                <livewire:admin::employee.modal.update/>
                <livewire:admin::employee.modal.reset-password/>
                @endAdminHasRole

                @AdminHasRole(['delete_employees'])
                <livewire:admin::employee.modal.delete/>
                @endAdminHasRole
            </div>
        </div>
    </div>

@endsection
