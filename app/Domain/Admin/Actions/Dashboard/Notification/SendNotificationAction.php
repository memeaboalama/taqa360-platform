<?php

namespace App\Domain\Admin\Actions\Dashboard\Notification;

use App\Domain\User\Repositories\UserRepository;
use App\Domain\AppBase\Repositories\NotificationRepository;

class SendNotificationAction
{
    public UserRepository $userRepository;
    public NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function handle(array $data)
    {
      
            try{

                //  dispatch_sync(new NotifyUserJob($data['title'], $data['body'], $data['platform'], $users));
              
              return $this->notificationRepository->store($data);

            }
            catch(\Exception $e)
            {

            }
        

    }

}
