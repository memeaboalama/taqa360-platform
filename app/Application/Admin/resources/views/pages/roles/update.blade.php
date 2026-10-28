@extends('admin::layout.layout')

@section('title',__('admin::home.admin') . " | " . __('admin::home.updateRole'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.updateRole') }}
                    </div>
                </div>
                <div class="card-body">
                    <livewire:admin::role.components.update :roleId="$roleId"/>
                </div>
            </div>
        </div>

    </div>
@endsection
