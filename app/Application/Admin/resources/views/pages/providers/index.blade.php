@extends('admin::layout.layout')
@section('title',__('admin::home.Drivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.Drivers') }}
                    </div>
{{--                    <div class="prism-toggle">--}}
{{--                        <button--}}
{{--                            class="btn btn-primary-light m-1"--}}
{{--                            data-bs-toggle="modal"--}}
{{--                            data-bs-target="#createCategory">Add New<i class="bi bi-plus-lg ms-2"></i></button>--}}
{{--                    </div>--}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::provider.table.all/>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
