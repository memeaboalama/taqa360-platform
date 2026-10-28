@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.notifications'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.notifications')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#sendNotification">{{__('admin::home.Create new Notification')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::notification.table.all/>
                    </div>
                </div>

                <livewire:admin::notification.component.send/>
                <livewire:admin::notification.component.show/>
                

            </div>
        </div>
    </div>

@endsection
