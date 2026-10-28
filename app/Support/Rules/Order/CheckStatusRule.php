<?php

namespace App\Support\Rules\Order;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Illuminate\Support\Facades\DB;

class CheckStatusRule implements ValidationRule
{
    // public int $order;
    public  $order;
    public array $status;

    // public function __construct(int $order = null, array $status)
    public function __construct( $order = null, array $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $order = MerchantOrder::find($this->order);

        if ($order && !in_array($order->status->value, $this->status))
            $fail($this->message());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_order_status');
    }
}
