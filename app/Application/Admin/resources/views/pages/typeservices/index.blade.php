@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.TypeService bind city'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.TypeService bind city')
                    </div>
              
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <livewire:admin::type-service.modal.create/>
                    </div>
                </div>

               
               
            </div>
        </div>
    </div>

@endsection
