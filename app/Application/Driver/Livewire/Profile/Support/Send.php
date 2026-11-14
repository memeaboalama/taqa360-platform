<?php

namespace Application\Driver\Livewire\Profile\Support;

use App\Domain\AppBase\Actions\ContactUs\SendMessageAction;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Send extends Component
{
    use LivewireAlert;

    public $name;
    public $email;
    public $phone;
    public $message;

    public function mount(): void
    {
        $driver = auth('driver')->user();
        $this->name = $driver->name;
        $this->email = $driver->email;
        $this->phone = $driver->phone;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'numeric', 'digits_between:9,10', new IsValidPhone],
            'message' => 'required|string|min:6|max:255',
        ];
    }

    public function sendMessage(SendMessageAction $sendMessageAction): void
    {
        $data = $this->validate();
        $data['source'] = 'driver_portal';

        DB::transaction(function () use ($sendMessageAction, $data): void {
            $sendMessageAction->handle($data);
        });

        $this->alert('success', __('driver::home.data_saved_successfully'));
        $this->reset('message');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.profile.support.send');
    }
}
