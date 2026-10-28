@extends('driver::layout.layout')

@section('title',__('driver::home.Driver |Roles'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('driver::home.roles')
                    </div>
                    @DriverHasRole(['create_roles'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createRole">{{ __('driver::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endDriverHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:driver::role.table.all/>
                    </div>
                </div>
                @DriverHasRole(['create_roles'])
                <livewire:driver::role.modal.create/>
                @endDriverHasRole
                <livewire:driver::role.modal.update/>
            </div>
        </div>

    </div>
@endsection
