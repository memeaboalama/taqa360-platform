<div>
    test : {{auth('provider')->id()}}

    @if($showNewOrderNotification)
        testing notify you have new order waiting
        {{print_r(json_encode($data))}}
    @endif
</div>
