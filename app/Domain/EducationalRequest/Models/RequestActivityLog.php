<?php

namespace Domain\EducationalRequest\Models;

use Illuminate\Database\Eloquent\Model;

class RequestActivityLog extends Model
{
    protected $fillable = [
        'educational_request_id',
        'activity_type',
        'from_status',
        'to_status',
        'reason',
        'actor_name',
        'details',
    ];

    public function educationalRequest()
    {
        return $this->belongsTo(EducationalRequest::class);
    }
}
