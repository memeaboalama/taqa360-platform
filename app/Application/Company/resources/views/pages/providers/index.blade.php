@extends('company::layout.layout')
@section('title',"Company |" . __('company::home.providers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('company::home.providers')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createProvider">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:company::provider.table.all/>
                    </div>
                </div>

                <livewire:company::provider.modal.create/>
                <livewire:company::provider.modal.update/>
                <livewire:company::provider.modal.delete/>

            </div>
        </div>
    </div>

@endsection
