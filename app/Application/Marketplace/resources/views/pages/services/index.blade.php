@extends('marketplace::layout.layout')
@section('title',"Merchant |" . __('marketplace::home.services'))

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    {{__('marketplace::home.services')}}
                </div>
                <div class="prism-toggle">
                    <button class="btn btn-primary-light m-1" data-bs-toggle="modal" data-bs-target="#createService">@lang('marketplace::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <livewire:marketplace::service.table.all />
                </div>
            </div>

            <livewire:marketplace::service.modal.create />
            <livewire:marketplace::service.modal.delete />

        </div>
    </div>
</div>

@endsection
