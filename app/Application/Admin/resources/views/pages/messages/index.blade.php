@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . " | " . __('admin::home.Messages'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.Messages') }}
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::message.table.all/>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
