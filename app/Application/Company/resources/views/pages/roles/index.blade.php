@extends('company::layout.layout')

@section('title',__('company::home.company'). " | " .__('company::home.roles'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('company::home.roles')
                    </div>
                    @CompanyHasRole(['create_roles'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createRole">{{ __('company::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endCompanyHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:company::role.table.all/>
                    </div>
                </div>
                @CompanyHasRole(['create_roles'])
                <livewire:company::role.modal.create/>
                @endCompanyHasRole
                <livewire:company::role.modal.update/>
            </div>
        </div>

    </div>
@endsection
