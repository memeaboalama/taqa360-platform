<div>
    test order following : {{auth('web')->id()}}

    @if($showNewOrderNotification)
        testing notify you have new order updates
    <br>
        {{print_r(json_encode($data))}}
    @endif
</div>
