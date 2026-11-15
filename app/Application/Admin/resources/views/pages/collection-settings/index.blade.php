@extends('admin::layout.layout')
@section('title', 'إعدادات التحصيل والتسويات')

@section('content')
    <div class="col-12">
        <livewire:admin::collection-settings.index />
    </div>
@endsection
