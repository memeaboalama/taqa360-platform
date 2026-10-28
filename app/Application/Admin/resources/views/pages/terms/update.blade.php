@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Terms & Conditions'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!-- Start:: row-1 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <livewire:admin::terms.update />
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->
        </div>
    </div>

@endsection
