@extends('admin::layout.layout')
@section('title',__('admin::home.Merchant'). " |" . __('admin::home.show'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!-- Start:: row-3 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                {{ $menu->title }}
                            </div>
                            <livewire:admin::merchant.tabs.services.accept :menu="$menu"/>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3 nav-justified nav-style-1 d-sm-flex d-block" role="tablist" wire:ignore>
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#home1-justified" aria-selected="false">@lang('admin::home.Menu Details')</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <livewire:admin::merchant.tabs.services.update :menu="$menu" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-3 -->

        </div>
    </div>
@endsection
