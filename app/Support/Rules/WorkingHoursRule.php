<?php

namespace App\Support\Rules;

use App\Domain\MarketPlace\Repositories\Branch\WorkingDays\WorkingDaysRepository;
use Closure;
use Domain\MarketPlace\Models\MerchantBranchWorkday;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class WorkingHoursRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $branch_id;
    public function __construct($branch_id, $items)
    {
        $this->branch_id = $branch_id;
        $this->items = $items['items'];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dayNameOffset = Str::between($attribute, 'items.', '.hours');
        $timeOffset = (int)Str::between($attribute, 'hours.', '.from');
        $day_id = $this->items[$dayNameOffset]['id'];
        $slot_to = $this->items[$dayNameOffset]['hours'][$timeOffset]['to'];

        if (strtotime($value) >= strtotime($slot_to))
            $fail($this->message()['greater_than']);

        if (empty($value) || empty($slot_to))
            return;

        $slot_id = $this->items[$dayNameOffset]['hours'][$timeOffset]['id'];
        $workingDaysRepository = new WorkingDaysRepository(new MerchantBranchWorkday());
        $workingDay = $workingDaysRepository
            ->getMerchantBranchByDay(['day_id'=> $day_id, 'merchant_branch_id'=> $this->branch_id]);

        if ($workingDay){
            $timesCount = $workingDay->workHours()
                ->where('id', '!=' , $slot_id)
                ->whereBetween('from', [$value, $slot_to])
                ->whereBetween('to', [$value, $slot_to])
                ->count();
            if ($timesCount > 0)
                $fail($this->message()['not_available']);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            'greater_than' => __('marketplace::messages.WORKING_FROM_GREATER_THAN_TO'),
            'not_available' => __('marketplace::messages.WORKING_HOUR_NOT_AVAILABLE'),
        ];
    }
}
