@extends('driver::layout.layout')

@section('title', __('driver::home.my_custody'))

@section('content')
    <div class="col-12">
        <livewire:driver::custody.statement />
    </div>
@endsection
