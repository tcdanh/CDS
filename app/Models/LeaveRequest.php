<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_TCHC_REVIEWED = 'tchc_reviewed';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'start_date',
        'end_date',
        'days_requested',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_requested' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'TCHC chưa kiểm tra',
            self::STATUS_TCHC_REVIEWED => 'TCHC đã kiểm tra - Lãnh đạo chưa duyệt',
            self::STATUS_APPROVED => 'Lãnh đạo đã duyệt',
            self::STATUS_REJECTED => 'Lãnh đạo từ chối',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusStyles(): array
    {
        return [
            self::STATUS_PENDING => 'bg-secondary',
            self::STATUS_TCHC_REVIEWED => 'bg-info text-dark',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
        ];
    }
}
