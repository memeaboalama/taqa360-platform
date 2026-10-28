@extends('admin::layout.layout')
@section('title',"Admin |" . __('admin::lang.Towing Lines'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.Towing Lines')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createTowingLine">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::towing.line.table.all/>
                    </div>
                </div>

                <livewire:admin::towing.line.modal.create/>
                <livewire:admin::towing.line.modal.update/>
                <livewire:admin::towing.line.modal.delete/>
            </div>
        </div>
    </div>

@endsection
