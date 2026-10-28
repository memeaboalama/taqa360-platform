<?php

namespace Application\Marketplace\Livewire\Profile\Support;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\Support\SendMessageAction;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Send extends Component
{
    use LivewireAlert;

    public $name, $email, $phone, $message, $merchant;

    public function mount(FetchSingleMerchantAction $fetchSingleMerchantAction)
    {
        $this->merchant = $fetchSingleMerchantAction->handle(resolve('_InitialMerchant'));
        $this->name = $this->merchant->user_name;
        $this->email = $this->merchant->email;
        $this->phone = $this->merchant->phone;
    }

    public function nickNames()
    {
        return [
            'email' => trans('marketplace::home.Email Address'),
            'phone' => trans('marketplace::home.Phone Number'),
            'name' => trans('marketplace::home.name'),
            'message' => trans('marketplace::home.message'),
        ];
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'phone' => ['required','numeric','digits_between:9,10',new isValidPhone],
            'name' => 'required|string',
            'message' => 'required|string|min:6',
        ];
    }

    public function sendMessage(SendMessageAction $sendMessageAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $sendMessageAction->handle($data);
        $this->alert('success', __('marketplace::home.data_saved_successfully'));
        $this->reset('email', 'phone', 'name', 'message');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.profile.support.send');
    }
}
