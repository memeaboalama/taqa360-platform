@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Cash back'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Cash back')
                    </div>
                    
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createCashBack">{{__('admin::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                         <livewire:admin::cash-back.table.all/> 
                    </div>
                </div>

                <!-- @AdminHasRole(['create_coupons']) -->
                <livewire:admin::cash-back.modal.create/> 
                <!-- @endAdminHasRole -->

                <!-- @AdminHasRole(['edit_coupons']) -->
                <livewire:admin::cash-back.modal.update/> 
                <!-- @endAdminHasRole -->

                <!-- @AdminHasRole(['delete_coupons']) -->
                <livewire:admin::cash-back.modal.delete/> 
                <!-- @endAdminHasRole -->
                <livewire:admin::cash-back.modal.enable/> 


            </div>
        </div>
    </div>

@endsection
