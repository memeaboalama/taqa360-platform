<?php

namespace App\Domain\AppBase\Actions\ContactUs;

use App\Domain\AppBase\Repositories\MessageRepository;

class SendMessageAction
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function handle(array $data = [])
    {
        return $this->messageRepository->store($data);
    }
}
