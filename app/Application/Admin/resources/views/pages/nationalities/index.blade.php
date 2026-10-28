@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::lang.nationalities'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.nationalities')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createNationality">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::nationality.table.all/>
                    </div>
                </div>

                <livewire:admin::nationality.modal.create/>
                <livewire:admin::nationality.modal.update/>
                <livewire:admin::nationality.modal.delete/>
            </div>
        </div>
    </div>

@endsection
