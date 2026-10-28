<?php

namespace Domain\Educator\Models;

use App\Application\Educator\Enums\EducatorStatus;
use App\Domain\AppBase\Models\Bank;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Image\HasGallery;
use App\Support\Traits\Models\HasOtp;
use Domain\Notification\Models\DeviceToken;
use Domain\Project\Models\Assignment;
use Domain\Project\Models\Bid;
use Domain\Project\Models\SubTask;
use Domain\Project\Models\Task;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Educator extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasOtp, HasGallery;
    use SoftDeletes, HasUuid;

    public array $translatable = [];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'discipline',
        'school_name',
        'accepted_terms',
        'dialcode',
        'country_name',
        'country_code',
        'last_logged_in_at',
        'phone_verified_at',
        'email_verified_at',
        'rating',
        'availability_status',
        'status',
        'rejection_reason',
        'is_accepted',
    ];
    protected $casts = [
        'accepted_terms' => 'boolean',
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_logged_in_at' => 'datetime',
        'is_accepted' => 'boolean',
        'rating' => 'float',
        'availability_status' => StatusEnum::class,
        'status' => EducatorStatus::class,
    ];


    public function Bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function profile()
    {
        return $this->hasOne(EducatorProfile::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(EducatorLanguage::class);
    }

    public function files()
    {
        return $this->morphMany(EducatorFile::class, 'fileable');
    }

    public function achievements()
    {
        return $this->hasMany(EducatorAchievement::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_to', 'id');
    }

    public function bios()
    {
        return $this->hasOne(EducatorBio::class);
    }

    public function socialLinks()
    {
        return $this->hasMany(EducatorSocialLink::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(EducatorService::class);
    }

    public function academicMajors(): HasMany
    {
        return $this->hasMany(EducatorAcademicMajor::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(EducatorSkill::class);
    }

    public function bankInfo(): HasOne
    {
        return $this->hasOne(EducatorBankInfo::class);
    }

    public function subTasks()
    {
        return $this->morphMany(SubTask::class, 'creatable');
    }

    public function filesCompleted(): bool
    {
        $hasFront = $this->files()->where('collection_name', 'identity_front')->exists();

        if ($this->profile?->identity_type === 'passport') {
            $hasSecond = $this->files()->where('collection_name', 'passport_image')->exists();
        } else {
            $hasSecond = $this->files()->where('collection_name', 'identity_back')->exists();
        }

        $hasProfileImage = $this->files()->where('collection_name', 'profile_image')->exists();

        return $hasFront && $hasSecond && $hasProfileImage;
    }

    public function getIdentityFrontImageUrlAttribute()
    {
        $file = $this->files()->where('collection_name', 'identity_front')->first();
        return $file ? Storage::disk($file->storage)->url($file->path) : null;
    }


    public function getIdentityBackImageUrlAttribute()
    {
        $file = $this->files()->where('collection_name', 'identity_back')->first();
        return $file ? Storage::disk($file->storage)->url($file->path) : null;
    }

    public function getIdentityFrontImageAttribute(): ?EducatorFile
    {
        return $this->files()->where('collection_name', 'identity_front')->first();
    }

    public function getIdentityBackImageAttribute(): ?EducatorFile
    {
        return $this->files()->where('collection_name', 'identity_back')->first();
    }

    public function getPassportImageAttribute(): ?EducatorFile
    {
        return $this->files()->where('collection_name', 'passport_image')->first();
    }

    public function getProfileImageAttribute(): ?EducatorFile
    {
        return $this->files()->where('collection_name', 'profile_image')->first();
    }

    public function getProfileImageUrlAttribute(): string
    {
        return $this->profileImage?->url ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=F8C421&color=333333';
    }

    public function device_tokens()
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }

    public function acceptedAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'assigned_to')
            ->where('status', 'accepted');
    }

    public function activeTasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Task::class,
            Assignment::class,
            'assigned_to', // Foreign key on assignments table
            'id', // Foreign key on tasks table
            'id', // Local key on educators table
            'task_id' // Local key on assignments table
        )->where('assignments.status', 'accepted')
            ->where('tasks.status', 'IN_PROGRESS')
            ->whereNull('tasks.deleted_at')
            ->whereNull('assignments.deleted_at')
            ->select('tasks.*', 'assignments.assigned_to');
    }

    public function overdueTasks(): HasManyThrough
    {
        return $this->activeTasks()
            ->where('tasks.deadline', '<', now()->toDateString());
    }

    public function completedTasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Task::class,
            Assignment::class,
            'assigned_to',
            'id',
            'id',
            'task_id'
        )->where('assignments.status', 'accepted')
            ->where('tasks.status', 'COMPLETED')
            ->whereNull('tasks.deleted_at')
            ->whereNull('assignments.deleted_at')
            ->select('tasks.*', 'assignments.assigned_to');
    }

    public function assignedTasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Task::class,
            Assignment::class,
            'assigned_to',
            'id',
            'id',
            'task_id'
        )->where('assignments.status', 'accepted')
            ->whereNull('tasks.deleted_at')
            ->whereNull('assignments.deleted_at')
            ->select('tasks.*', 'assignments.assigned_to');
    }
}
