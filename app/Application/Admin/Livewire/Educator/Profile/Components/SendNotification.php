<?php

namespace Application\Admin\Livewire\Educator\Profile\Components;

use App\Domain\AppBase\Models\NotificationNew;
use Domain\Educator\Models\Educator;
use Domain\Notification\Models\DeviceToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Livewire\Component;

class SendNotification extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public string $title = '';
    public string $body = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:500',
        ];
    }

    public function send()
    {
        $data = $this->validate();

        DB::beginTransaction();
        try {
            NotificationNew::create([
                'title' => $data['title'],
                'body' => $data['body'],
                'channel' => 'mobile',
                'device_type' => Educator::class,
                'educator_id' => $this->educator->id,
                'admin_id' => auth()->guard('admin')->user()->id,
            ]);

            $this->sendPushNotification($data['title'], $data['body']);

            $this->emit('closeModal', '#sendEducatorNotification');
            $this->reset('title', 'body');
            $this->alert('success', __('admin::home.sent_successfully'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SendEducatorNotification Error: ' . $e->getMessage());
            $this->alert('error', __('admin::home.something_went_wrong'));
        }
    }

    protected function sendPushNotification(string $title, string $body): void
    {
        $tokens = DeviceToken::where('deviceable_type', Educator::class)
            ->where('deviceable_id', $this->educator->id)
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return;
        }

        try {
            $firebase = (new Factory)
                ->withServiceAccount(base_path('config/firebase_credentials.json'));

            $messaging = $firebase->createMessaging();

            $message = CloudMessage::fromArray([
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ]);

            $messaging->sendMulticast($message, $tokens);
        } catch (\Exception $e) {
            Log::warning('FCM push skipped for educator ' . $this->educator->id . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('admin::livewire.educator.profile.components.send-notification');
    }
}