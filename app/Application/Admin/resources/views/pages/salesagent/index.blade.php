@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.ApprovedSalseAgents'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::sales-agent.table.filter/>
                    </div>

                    @AdminHasRole('add_salesagents')
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createSalseAgents">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        @AdminHasRole('show_sales agent')
                        <livewire:admin::sales-agent.table.all/>
                        @endAdminHasRole
                    </div>
                </div>

                <livewire:admin::sales-agent.modal.create />
                @AdminHasRole('delete_sales agent')
                <livewire:admin::sales-agent.modal.delete/>
                @endAdminHasRole
                <livewire:admin::sales-agent.modal.reset-password/>
            </div>
        </div>
    </div>

{{--    @push('js')--}}

{{--        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj1qLmrodsYHebNZUltIb20_FrZn7DVs4&callback=initMap"></script>--}}
{{--    @endpush--}}

@endsection
