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
                    @AdminHasRole(['send_notifications', 'send notifications_general'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#sendNotification">{{__('admin::home.Create new Notification')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @AdminHasRole(['show_notifications', 'send_notifications', 'send notifications_general'])
                            <livewire:admin::notification.table.all/>
                        @endAdminHasRole
                    </div>
                </div>

                @AdminHasRole(['send_notifications', 'send notifications_general'])
                    <livewire:admin::notification.component.send/>
                @endAdminHasRole
                @AdminHasRole(['show_notifications', 'send_notifications', 'send notifications_general'])
                    <livewire:admin::notification.component.show/>
                @endAdminHasRole
                

            </div>
        </div>
    </div>

@endsection
