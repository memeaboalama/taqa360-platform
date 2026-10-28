<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Profile\Support;

use App\Domain\AppBase\Repositories\MessageRepository;

class SendMessageAction
{
    protected MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;

    }
    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->messageRepository->store($data);
    }
}
