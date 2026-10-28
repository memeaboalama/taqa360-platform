<?php

namespace App\Domain\Educator\Resource\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->uuid,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'organization' => $this->organization,
            'documentUrl' => $this->file?->url ?? null,
        ];


        if ($this->type === 'work_experience') {
            $data['startDate'] = optional($this->start_date)?->format('Y-m-d');
            $data['endDate'] = optional($this->end_date)?->format('Y-m-d');
        } elseif ($this->type === 'certificate' || $this->type === 'course' || $this->type === 'workshop') {
            $data['issueDate'] = optional($this->issue_date)?->format('Y-m-d');
        }

        return $data;
    }
}
