@extends('admin::layout.layout')
@section('title', __('admin::home.collection_failure_report_details'))

@section('content')
    <div class="col-12">
        <livewire:admin::collection-failure-reports.show :report="$report" />
    </div>
@endsection
