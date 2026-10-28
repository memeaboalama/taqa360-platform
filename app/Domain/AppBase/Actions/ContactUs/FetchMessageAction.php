<?php

namespace App\Domain\AppBase\Actions\ContactUs;

use App\Domain\AppBase\Repositories\MessageRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchMessageAction
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function handle(array $data = []) : LengthAwarePaginator|Collection
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->messageRepository->paginate(cols: ['id','name', 'email', 'phone', 'message'], paginate: $per_page);
        }
        return $this->messageRepository->all(['id','name', 'email', 'phone', 'message']);
    }
}
