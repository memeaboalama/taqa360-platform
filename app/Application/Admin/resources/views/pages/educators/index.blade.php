@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Approved Educators'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::educator.table.filter/>
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createEducator">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::educator.table.all/>
                    </div>
                </div>

                <livewire:admin::educator.modal.create/>
                <livewire:admin::educator.modal.approve/>
                <livewire:admin::educator.modal.delete/>
                {{-- <livewire:admin::educator.modal.history/> --}}
            </div>
        </div>
    </div>

{{--    @push('js')--}}

{{--        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj1qLmrodsYHebNZUltIb20_FrZn7DVs4&callback=initMap"></script>--}}
{{--    @endpush--}}

@endsection
