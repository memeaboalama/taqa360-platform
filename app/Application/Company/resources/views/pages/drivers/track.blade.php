@extends('company::layout.layout')
@section('title', __('company::home.company') . " | " . __('company::home.trackingDrivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('company::home.trackingDrivers')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:company::driver.map.track/>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
