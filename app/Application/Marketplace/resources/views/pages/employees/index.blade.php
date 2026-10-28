@extends('marketplace::layout.layout')

@section('title',__('marketplace::home.Merchant |Employees'))
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
                        @lang('marketplace::lang.employees')
                    </div>
                    @MerchantHasRole(['create_employees'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createEmployee">{{ __('marketplace::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endMerchantHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:marketplace::employee.table.all/>
                    </div>
                </div>
                @MerchantHasRole(['create_employees'])
                <livewire:marketplace::employee.modal.create/>
                @endMerchantHasRole

                <livewire:marketplace::employee.modal.update/>
                @MerchantHasRole(['edit_employees'])
                <livewire:marketplace::employee.modal.updatepassword/>
                @endMerchantHasRole
                @MerchantHasRole(['delete_employees'])
                <livewire:marketplace::employee.modal.delete/>
                @endMerchantHasRole
            </div>
        </div>
    </div>
@endsection
