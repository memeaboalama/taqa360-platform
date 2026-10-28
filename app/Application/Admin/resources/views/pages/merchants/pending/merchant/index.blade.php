@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.pending merchants'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('admin::home.pending merchants') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::merchant.table.pending.merchant.all/>
                    </div>
                </div>
                <livewire:admin::merchant.modal.activate/>
                <livewire:admin::merchant.modal.deactivate/>
                <livewire:admin::merchant.modal.reset-password/>
            </div>
        </div>
    </div>

@endsection
