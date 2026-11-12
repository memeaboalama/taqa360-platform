
<?php

namespace Domain\EducationalRequest\Models;

use App\Application\Educational\Enums\EducationalRequestStatus;
use App\Application\Educational\Enums\EducationalRequestType;
use App\Domain\User\Models\User;
use App\Support\Enum\PaymentStatusEnum;
use Domain\Admin\Models\Admin;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Domain\AppBase\Models\Edu\Service;
use Domain\Project\Models\Project;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalRequest extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'title',
        'service_id',
        'description',
        'requester_id',
        'academic_major_id',
        'education_level',
        'type',
        'status',
        'execution_status',
        'budget',
        'agreed_amount',
        'payment_status',
        'final_agreement',
        'urgency_level',
        'additional_notes',
        'project_site_type',
        'solar_system_type',
        'accepted_at',
        'rejected_at',
        'completed_at',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'type' => EducationalRequestType::class,
        'status' => EducationalRequestStatus::class,
        'execution_status' => EducationalRequestStatus::class,
        'payment_status' => PaymentStatusEnum::class,
        'budget' => 'decimal:2',
        'agreed_amount' => 'decimal:2',
        'reviewed_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function academicMajor()
    {
        return $this->belongsTo(AcademicMajor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return $this->service?->name ?? ($this->type instanceof EducationalRequestType
            ? $this->type->trans()
            : (string) $this->type);
    }

    public function attachments()
    {
        return $this->morphMany(EducationalRequestFile::class, 'fileable');
    }

    public function files()
    {
        return $this->attachments();
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'educational_request_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function activityLog()
    {
        return $this->hasMany(RequestActivityLog::class)->latest('created_at');
    }

    public function getCodeAttribute(): string
    {
        return '#' . str_pad($this->id ?? 0, 5, '0', STR_PAD_LEFT);
    }
}
