@extends('driver::layout.layout')

@section('title', __('driver::home.my_custody'))

@section('content')
    <div class="col-12">
        <livewire:admin::driver-custody.statement :driver="$driver" />
    </div>
    <livewire:driver::home.order.modal.show />
@endsection
