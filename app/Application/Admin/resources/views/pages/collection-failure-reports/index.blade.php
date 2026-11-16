@extends('admin::layout.layout')
@section('title', __('admin::home.collection_failure_reports'))

@section('content')
    <div class="col-12">
        <livewire:admin::collection-failure-reports.index />
    </div>
@endsection
