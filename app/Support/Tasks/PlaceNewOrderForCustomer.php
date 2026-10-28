<?php

namespace App\Support\Tasks;


use App\Abstract\AbstractOrderProcess;

class PlaceNewOrderForCustomer extends AbstractOrderProcess
{
    public array $tasks = [
        CreateNewOrderRecord::class,
        ChargeCustomerForOrder::class,
        SendConfirmationEmail::class,
        SendOrderToStore::class,
    ];

}
