<?php

namespace App\Http\Livewire;

use App\Support\Enum\NetworkStatusEnum;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ProviderPresence extends Component
{
    public $text = '';
    public function getListeners()
    {
        return [
            //Presence Channel
            "echo-presence:providers-presence.1,listen,.SubscribedEvent" => 'listen',    // Listen

            "echo-presence:providers-presence.1,here" => 'here',            // Here
            "echo-presence:providers-presence.1,joining" => 'joining',         // Joining
            "echo-presence:providers-presence.1,leaving" => 'leaving',         // Leaving
        ];
    }

    public function listen()
    {
        $this->text = 'listen';

    }


    public function here()
    {
        $this->text = 'here';
        auth('provider')->user()->update(['network_status'=>NetworkStatusEnum::ONLINE]);

    }

    public function joining()
    {
        $this->text = 'joining';

    }

    public function leaving()
    {
        $this->text = 'leaving';
        Log::channel('single')->info($this->text);

    }
    public function render()
    {
        return view('livewire.provider-presence');
    }
}
