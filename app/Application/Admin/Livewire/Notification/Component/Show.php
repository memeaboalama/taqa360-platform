<?php

namespace Application\Admin\Livewire\Notification\Component;

use App\Domain\AppBase\Models\NotificationNew;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use LivewireAlert;

    public ?NotificationNew $notification = null;

    protected $listeners = ['showNotification'];

    public function showNotification($id): void
    {
        $this->notification = NotificationNew::with('admin')->find($id);
        $this->emit('openModal', '#showNotification');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.notification.component.show');
    }
}
