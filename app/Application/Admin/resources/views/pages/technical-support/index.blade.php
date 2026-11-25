@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . ' | ' . __('admin::home.Technical Support'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <livewire:admin::settings.technical-support />
    </div>
@endsection
