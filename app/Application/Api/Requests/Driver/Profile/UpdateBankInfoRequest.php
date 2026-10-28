<?php

namespace App\Application\Api\Requests\Driver\Profile;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use App\Support\Rules\MaxWordCount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
class UpdateBankInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'bank_id' => ['required', Rule::exists('banks','id')],
            'cvv' => ['required', new CardCvc($this->get('card_number'))],
            'name_on_card' => ['required','string',new MaxWordCount],
            'card_number' => ['required', new CardNumber],
            'expire_month' => ['required', new CardExpirationMonth($this->get('expire_year'))],
            'expire_year' => ['required', new CardExpirationYear($this->get('expire_month'))],



        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
