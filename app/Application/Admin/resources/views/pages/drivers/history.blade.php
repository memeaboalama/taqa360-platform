@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Actions Drivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::driver.history.filter/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::driver.history.all/>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
