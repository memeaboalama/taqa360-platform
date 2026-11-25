@extends('marketplace::layout.layout')
@section('title', __('marketplace::products.edit_title'))
@push('css')
    <style>
        .page-header-breadcrumb { display: none !important; }
        .product-editor { margin-top: 1.5rem; }
        .product-editor .editor-multiselect { position: relative; }
        .product-editor .editor-multiselect summary {
            cursor: pointer;
            list-style: none;
            overflow: hidden;
            padding-inline-end: 2.5rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .product-editor .editor-multiselect summary::-webkit-details-marker { display: none; }
        .product-editor .editor-multiselect-options {
            background: #fff;
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: .375rem;
            box-shadow: 0 .25rem .75rem rgb(0 0 0 / 8%);
            left: 0;
            max-height: 220px;
            overflow-y: auto;
            padding: .5rem .75rem;
            position: absolute;
            right: 0;
            top: calc(100% + .25rem);
            z-index: 20;
        }
        .product-editor .editor-multiselect-option {
            align-items: center;
            cursor: pointer;
            display: flex;
            gap: .5rem;
            margin: 0;
            min-height: 36px;
        }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('marketplace:products') }}">{{ __('admin::main.lang.products') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('marketplace::products.edit_title') }}</li>
@endsection
@section('content')
    <livewire:marketplace::product.edit :product-id="$productId" />
@endsection
