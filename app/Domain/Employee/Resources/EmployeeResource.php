<?php

namespace App\Domain\Employee\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_manager' => $this->branchManager?->count() ? true : false,
            'email' => $this->email,
            'phone' => $this->phone,
            'dialcode' => $this->dialcode,
            'country_name' => $this->country_name,
            'country_code' => $this->country_code,
            'last_logged_in_at' => $this->last_logged_in_at?->diffForHumans(),
            'branch' => ($this->branch->first()?->title)
                ? $this->branch->first()?->title
                : $this->branchManager->first()?->title,

            'merchant' => ($this->branch->first()?->merchant?->company_name)
                ? $this->branch->first()?->merchant?->company_name
                : $this->branch->first()?->branchManager?->company_name,
        ];
    }
}
