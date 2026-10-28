@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " .  __('admin::home.Tracking Merchants'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Tracking Merchants')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::merchant.map.track/>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

