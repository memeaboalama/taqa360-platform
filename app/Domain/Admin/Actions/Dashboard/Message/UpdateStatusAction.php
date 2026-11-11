<?php

namespace App\Domain\Admin\Actions\Dashboard\Message;

use App\Domain\AppBase\Repositories\MessageRepository;

class UpdateStatusAction
{
    protected MessageRepository $messageRepository;


    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }


    public function handle(int $element_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $message = $this->messageRepository->findByCols(['id' => $element_id]);
        if (!$message) {
            return null;
        }

        $message->update($data);

        return $message;
    }
}
