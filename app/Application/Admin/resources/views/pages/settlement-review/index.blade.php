@extends('admin::layout.layout')
@section('title', __('admin::home.driver_settlements'))

@section('content')
    <div class="col-12">
        <livewire:admin::settlement-review.index />
    </div>
@endsection
