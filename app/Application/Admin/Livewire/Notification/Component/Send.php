<?php

namespace Application\Admin\Livewire\Notification\Component;

use App\Domain\Admin\Actions\Dashboard\Notification\SendNotificationAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Application\Api\Controllers\NotificationController;
use Illuminate\Support\Facades\Log;

class Send extends Component
{
    use LivewireAlert;

    public  $title = "";
    public  $body = "";
    public $device_type = "App\\Domain\\User\\Models\\User";
    public $channel = "mobile";
    public $platform = "ios";
    public $admin_id ;
    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'title.en' => 'title'
        ];
    }

    protected function rules(): array
    {
        // return [
        //     'title' => 'required|array',
        //     'title.*' => 'required|string|min:2|max:120',
        //     'body' => 'nullable|array',
        //     'body.*' => 'nullable|string|min:2|max:120',
        //     'channel' => 'required|string|in:mobile,web',
        //     'platform' => 'required|string|in:andriod,ios',
        // ];
        return [
            'title' => 'required|string|min:2|max:120',
            'body' => 'required|string|min:2|max:120',
            'channel' => 'required|string|in:mobile,web',
            'device_type' => 'required|string',
            'admin_id' => 'integer',
        ];
    }

    public function send(NotificationController $notificationController,SendNotificationAction $sendNotificationAction): void
    {
        try{

            $this->admin_id = auth()->guard('admin')->user()->id;
            $data = $this->validate();
            DB::beginTransaction();
            $notificationController->sendPushNotification($data);
            $sendNotificationAction->handle($data);
            $this->emit('refreshDatatable');
            $this->emit('closeModal', '#sendNotification');
            $this->reset('title','body','channel','device_type','admin_id');
            $this->alert('success', __('admin::home.sent_successfully'));
            DB::commit();
        }
        catch(\Exception $ex)
        {
            DB::rollBack();
            Log::error( $ex->getMessage());
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.notification.component.send');
    }
}
