@extends('admin::layout.layout')
@section('title', __('admin::home.driver_custody'))

@section('content')
    <div class="col-12">
        <livewire:admin::driver-custody.index />
    </div>
@endsection
