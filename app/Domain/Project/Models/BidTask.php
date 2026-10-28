<?php

namespace Domain\Project\Models;

use App\Application\Project\Enums\BidStatus;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidTask extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'bid_id',
        'task_id',
        'status',
        'amount',
        'rejection_reason',
        'approval_message',
    ];

    protected $casts = [
        'status' => BidStatus::class,
        'amount' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', BidStatus::APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', BidStatus::REJECTED);
    }

    public function scopePending($query)
    {
        return $query->where('status', BidStatus::PENDING);
    }

    public function approve(string $message = null): void
    {
        $this->update([
            'status' => BidStatus::APPROVED,
            'approval_message' => $message,
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => BidStatus::REJECTED,
            'rejection_reason' => $reason,
            'approval_message' => null,
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === BidStatus::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === BidStatus::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === BidStatus::REJECTED;
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->amount;
    }

}
