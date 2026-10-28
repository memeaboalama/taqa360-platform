@extends('company::layout.layout')
@section('title',__('company::home.company') . " | " . __('company::home.drivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:company::driver.components.filter/>
                    </div>
                    @CompanyHasRole(['create_drivers'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createDriver">{{ __('company::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endCompanyHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:company::driver.table.all/>
                    </div>
                </div>

                <livewire:company::driver.modal.create/>
                <livewire:company::driver.modal.resetpassword/>
                <livewire:company::driver.modal.elmerrors/>
            </div>
        </div>
    </div>

@endsection
