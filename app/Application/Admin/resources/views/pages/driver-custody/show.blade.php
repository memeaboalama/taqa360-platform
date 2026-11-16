@extends('admin::layout.layout')
@section('title', __('admin::home.driver_custody_statement'))

@section('content')
    <div class="col-12">
        <livewire:admin::driver-custody.statement :driver="$driver" />
    </div>
@endsection
