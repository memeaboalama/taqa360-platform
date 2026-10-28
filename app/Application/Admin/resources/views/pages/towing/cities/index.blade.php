@extends('admin::layout.layout')
@section('title',"Admin |" . __('admin::lang.Towing Cities'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.Towing Cities')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createTowingCity">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::towing.city.table.all/>
                    </div>
                </div>

                <livewire:admin::towing.city.modal.create/>
                <livewire:admin::towing.city.modal.update/>
                <livewire:admin::towing.city.modal.delete/>
            </div>
        </div>
    </div>

@endsection
