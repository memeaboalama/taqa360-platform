@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Pending Drivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::sales-agent.table.filter/>
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createSalseAgents">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::sales-agent.pending.table.all/>
                    </div>
                </div>

                <livewire:admin::sales-agent.modal.create/>
                <livewire:admin::sales-agent.modal.delete/>
                <livewire:admin::sales-agent.modal.approve/>
                <livewire:admin::sales-agent.modal.reset-password/>
            </div>
        </div>
    </div>

@endsection
