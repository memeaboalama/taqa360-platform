@extends('driver::layout.layout')
@section('title',__('driver::home.Verify Email'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-3"></div>
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">{{__('driver::home.Verify Email')}}</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed align-items-center justify-content-between">
                            <livewire:driver::profile.verify.email />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3"></div>
        </div>
    </div>
@endsection
