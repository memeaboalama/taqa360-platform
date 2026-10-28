@extends('admin::layout.layout')
@section('title',__('admin::home.Merchant'). " |" . __('admin::home.items'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.items')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::merchant.tabs.category.item.all :category_id="$category_id" />
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
