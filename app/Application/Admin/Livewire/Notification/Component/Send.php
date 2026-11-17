<?php

namespace Application\Admin\Livewire\Notification\Component;

use App\Domain\Admin\Actions\Dashboard\Notification\SendNotificationAction;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;
use App\Domain\User\Models\User;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\MerchantEmployee;
use App\Domain\AppBase\Models\NotificationNew;

class Send extends Component
{
    use LivewireAlert;

    public  $title = "";
    public  $body = "";
    public $device_type = User::class;

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'title.en' => 'title'
        ];
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:120',
            'body' => 'required|string|min:2|max:120',
            'device_type' => [
                'required',
                'string',
                Rule::in([User::class, Driver::class, MerchantEmployee::class]),
            ],
        ];
    }

    public function send(SendNotificationAction $sendNotificationAction): void
    {
        $admin = auth()->guard('admin')->user();
        abort_unless(
            $admin && ($admin->type === 'admin' || $admin->hasAnyPermission(['send_notifications', 'send notifications_general'])),
            403
        );

        try {
            $data = $this->validate();
            $notification = $sendNotificationAction->handle($data + [
                'admin_id' => $admin->id,
            ]);

            $this->emit('refreshDatatable');

            if ($notification->delivery_status === NotificationNew::DELIVERY_NO_RECIPIENTS) {
                $this->emit('closeModal', '#sendNotification');
                $this->reset('title', 'body', 'device_type');
                $this->alert('warning', __('admin::home.notification_no_recipients'));
                return;
            }

            if ($notification->delivery_status === NotificationNew::DELIVERY_FAILED) {
                $this->emit('closeModal', '#sendNotification');
                $this->reset('title', 'body', 'device_type');
                $this->alert('error', __('admin::home.notification_delivery_failed'));
                return;
            }

            $this->emit('closeModal', '#sendNotification');
            $this->reset('title', 'body', 'device_type');

            if ($notification->delivery_status === NotificationNew::DELIVERY_PARTIAL) {
                $this->alert('warning', __('admin::home.notification_partially_sent', [
                    'sent' => $notification->sent_count,
                    'failed' => $notification->failed_count,
                ]));
                return;
            }

            $this->alert('success', __('admin::home.notification_sent_to_devices', [
                'count' => $notification->sent_count,
            ]));
        } catch (Throwable $exception) {
            Log::error('Unable to send admin notification.', ['exception' => $exception]);
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.notification.component.send');
    }
}
