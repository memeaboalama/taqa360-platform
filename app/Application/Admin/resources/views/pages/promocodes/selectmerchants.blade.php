@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.promocodes'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <livewire:admin::merchant.table.filter/>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::promocode.modal.selectmerchants :promocode_id="$id"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
