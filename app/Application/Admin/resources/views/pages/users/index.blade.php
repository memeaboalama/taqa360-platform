@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.customers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::user.table.filter/>
                    </div>

                    @AdminHasRole(['create_customers'])
{{--                    <div class="prism-toggle">--}}
{{--                        <button--}}
{{--                            class="btn btn-primary-light m-1"--}}
{{--                            data-bs-toggle="modal"--}}
{{--                            data-bs-target="#createCustomer">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>--}}
{{--                    </div>--}}
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::user.table.all/>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
