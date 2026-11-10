@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . " | " . __('admin::home.Edit'))

@section('content')
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">{{ $educator->name }}</h5>
            </div>
            <div class="card-body">
                <livewire:educator::profile.builder.steps.basic :educator="$educator" :is-admin-edit="true" />
            </div>
        </div>
    </div>
@endsection
