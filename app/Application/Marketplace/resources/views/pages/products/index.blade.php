@extends('marketplace::layout.layout')
@section('title',__('admin::main.home.admin') . " | " . __('admin::main.lang.products'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:marketplace::product.table.filter/>
                    </div>

                    {{-- @AdminHasRole('add_products') --}}
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createProduct">
                            {{ __('admin::main.actions.create') }} <i class="bi bi-plus-lg ms-2"></i>
                        </button>
                    </div>
                    {{-- @endAdminHasRole --}}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        {{-- @AdminHasRole('show_products') --}}
                        <livewire:marketplace::product.table.all/>
                        {{-- @endAdminHasRole --}}
                    </div>
                </div>

                {{-- @AdminHasRole('add_products') --}}
                <livewire:marketplace::product.modal.create />
                {{-- @endAdminHasRole --}}

                {{-- @AdminHasRole('edit_products') --}}
                <livewire:marketplace::product.modal.update/>
                {{-- @endAdminHasRole --}}

                {{-- @AdminHasRole('view_products') --}}
                <livewire:marketplace::product.modal.show />
                {{-- @endAdminHasRole --}}

                {{-- @AdminHasRole('delete_products') --}}
                <livewire:marketplace::product.modal.delete/>
                {{-- @endAdminHasRole --}}
            </div>
        </div>
    </div>

@endsection