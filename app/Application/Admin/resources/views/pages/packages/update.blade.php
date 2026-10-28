@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Update Package'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Update Package') #{{ $id }}
                    </div>
                </div>
                <div class="card-body">
                    <livewire:admin::package.modal.update :id="$id"/>
                </div>
            </div>
        </div>
    </div>

@endsection
