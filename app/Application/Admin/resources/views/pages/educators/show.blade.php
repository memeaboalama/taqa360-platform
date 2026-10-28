@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " .  __('admin::home.showProvider'))

@push('css')
    <style>
        /* :root {
            --bs-primary: #306FAA;
            --bs-primary-rgb: 48, 111, 170;
            --bs-body-bg: #f8f9fa;
            --bs-body-color: #333333;
            --bs-body-font-family: 'Cairo', sans-serif;
            --bs-card-border-radius: .75rem;
            --bs-card-box-shadow: 0 4px 12px rgba(0,0,0, .08);
            --bs-nav-pills-link-active-bg: var(--bs-primary);
        } */
        /* .btn-primary { --bs-btn-color: #333; } */
        /* .card.custom-card { box-shadow: var(--bs-card-box-shadow); border: none; } */
        /* .nav-pills .nav-link { font-weight: 600; color: #6c757d; } */
        /* .nav-pills .nav-link.active { color: #333; } */
        .profile-avatar { width: 100px; height: 100px; border: 4px solid var(--bs-primary); }
        .icon-sm { width: 16px; height: 16px; }
        /* .data-label { color: #6c757d; font-size: 0.9rem; } */
        /* .data-value { font-weight: 600; } */
    </style>

@endpush
@section('content')
    <livewire:admin::educator.profile.index :educator="$educator"/>
@endsection
