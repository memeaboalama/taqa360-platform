@extends('marketplace::layout.layout')
@section('title',__('marketplace::home.Merchant |') . __('marketplace::home.show menu'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!-- Start:: row-3 -->
            <div class="row">
                <div class="col-xl-4">
                    <livewire:marketplace::menu.component.menu.grid.menu/>
                </div>

                <div class="col-xl-3">
                    <livewire:marketplace::menu.component.category.grid.category/>
                </div>

                <div class="col-xl-5">
                    <livewire:marketplace::menu.component.item.grid.item/>
                </div>

            </div>
            <!-- End:: row-3 -->

        </div>
    </div>
@endsection