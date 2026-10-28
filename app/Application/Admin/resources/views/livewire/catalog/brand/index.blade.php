@extends('admin::layout.layout')
@section('title', "Admin | " . __('admin::lang.Brands'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.Brands')
                    </div>
                    {{-- @if(auth('admin')->user()->can('create_brands')) --}}
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            onclick="Livewire.emit('openCreateBrandModal')">@lang('admin::lang.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    {{-- @endif --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @livewire('admin::catalog.brand.table.all')
                    </div>
                </div>

                @livewire('admin::catalog.brand.modal.create')
                @livewire('admin::catalog.brand.modal.update')
                @livewire('admin::catalog.brand.modal.delete')
            </div>
        </div>
    </div>

@endsection
