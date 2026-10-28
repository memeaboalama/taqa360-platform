@extends('admin::layout.layout')

@section('title',__('admin::home.admin') . " | " . __('admin::home.createRole'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.createRole') }}
                    </div>
                </div>
                <div class="card-body">
                    <livewire:admin::role.components.create/>
                </div>
            </div>
        </div>

    </div>
@endsection
