@extends('driver::layout.layout')

@section('title', __('driver::home.my_settlements'))

@section('content')
    <div class="col-12">
        <livewire:driver::settlement.mysettlements />
    </div>
@endsection
