<?php

namespace App\Support\Rules\Cart;

use Domain\MarketPlace\Models\AdditionGroup;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Illuminate\Support\Facades\DB;

class CheckChoiceRule implements ValidationRule
{
    public int $merchant_id;
    public float $total;

    public function __construct(int $merchant_id)
    {
        $this->merchant_id = $merchant_id;
        $this->total = 0.00;
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
        $addition_group_choice = DB::table('addition_group_choice')->where('id', $value)->first();
        if ($addition_group_choice)
        {
            $addition_group = AdditionGroup::find($addition_group_choice->addition_group_id);
            if ($addition_group->merchant_id != $this->merchant_id)
                $fail($this->message());

            $this->total += $addition_group_choice->price;
            request()->merge(['additional_total' => $this->total]);
        }else{
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_choice');
    }
}
