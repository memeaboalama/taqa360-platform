@extends('admin::layout.layout')

@section('title', __('admin::lang.Banner Spaces Management'))

@section('content')
    <div class="main-container container-fluid">
        <div class="inner-body">

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <livewire:admin::banner-space.page-selector />

                            <hr class="my-4">

                            <livewire:admin::banner-space.space-cards />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:admin::banner-space.modal.create-space />
    <livewire:admin::banner-space.modal.delete-space />
    <livewire:admin::banner-space.modal.update-space />
@endsection

