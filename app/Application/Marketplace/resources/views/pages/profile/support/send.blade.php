@extends('marketplace::layout.layout')
@section('title',__('marketplace::home.Support'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed  align-items-center justify-content-between">
                            <livewire:marketplace::profile.support.send />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
