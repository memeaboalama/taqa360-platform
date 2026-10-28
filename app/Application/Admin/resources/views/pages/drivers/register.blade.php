@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Register Drivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::driver.table-regestier.filter/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::driver.table-regestier.all/>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    @push('js')--}}

{{--        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj1qLmrodsYHebNZUltIb20_FrZn7DVs4&callback=initMap"></script>--}}
{{--    @endpush--}}

@endsection
