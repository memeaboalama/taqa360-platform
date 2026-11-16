@extends('admin::layout.layout')
@section('title', __('admin::home.settlement_review'))

@section('content')
    <div class="col-12">
        <livewire:admin::settlement-review.show :settlement="$settlement" />
    </div>
@endsection
