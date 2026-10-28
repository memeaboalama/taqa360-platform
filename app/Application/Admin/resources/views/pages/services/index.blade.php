@extends('admin::layout.layout')
@section('title',"Admin | Services")

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Services
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createService">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::services.table.all/>
                    </div>
                </div>

{{--                <livewire:admin::articles.modal.create/>--}}
{{--                <livewire:admin::articles.modal.update/>--}}
{{--                <livewire:admin::articles.modal.delete/>--}}
            </div>
        </div>
    </div>

@endsection
