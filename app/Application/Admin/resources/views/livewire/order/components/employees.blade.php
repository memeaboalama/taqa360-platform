<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header  d-flex justify-content-between">
            <div class="card-title"> @lang('admin::home.Employee')</div>
            <a class="btn btn-success btn-wave btn-sm waves-effect waves-light" wire:click="$emit('assignEmployee', {{ $order->id }})">
                <i class="bi bi-plus-lg me-1 align-middle d-inline-block"></i>Assign Employee
            </a>
        </div>
        <div class="card-body p-0">
            @if($order?->employee)
                <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                    <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">
                                    {{ \Str::substr(ucfirst($order?->employee?->name),0,1) }}
                                    </span></div>
                    <div class="flex-fill">
                        <p class="mb-0">{{ $order?->employee?->name }}</p>
                        <p class="mb-0 text-muted fs-15">{{ $order?->employee?->email }}</p>
                        <p class="mb-0 text-muted fs-15">{{ $order?->employee?->phone }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(!$order?->employee)
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg class="flex-shrink-0 me-2 svg-danger" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M15.73,3H8.27L3,8.27v7.46L8.27,21h7.46L21,15.73V8.27L15.73,3z M19,14.9L14.9,19H9.1L5,14.9V9.1L9.1,5h5.8L19,9.1V14.9z"></path><rect height="6" width="2" x="11" y="7"></rect><rect height="2" width="2" x="11" y="15"></rect></g></g></g></svg>
            <div>
                No Employees assigned, please assign employee.
            </div>
        </div>
    @endif
</div>
<livewire:admin::order.modal.assign/>
