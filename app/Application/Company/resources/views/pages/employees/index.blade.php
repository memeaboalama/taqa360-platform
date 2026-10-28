@extends('company::layout.layout')

@section('title',__('company::home.company'). " | ". __('company::home.employees'))
@push('js')
    <!-- Show Password JS -->
    <script src="{{ asset('assets/vendor/js/show-password.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('company::home.employees')
                    </div>
                    @CompanyHasRole(['create_employees'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createEmployee">{{ __('company::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endCompanyHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:company::employee.table.all/>
                    </div>
                </div>
                @CompanyHasRole(['create_employees'])
                <livewire:company::employee.modal.create/>
                @endCompanyHasRole

                <livewire:company::employee.modal.update/>
                @CompanyHasRole(['edit_employees'])
                <livewire:company::employee.modal.updatepassword/>
                @endCompanyHasRole
                @CompanyHasRole(['delete_employees'])
                <livewire:company::employee.modal.delete/>
                @endCompanyHasRole
            </div>
        </div>
    </div>
@endsection
