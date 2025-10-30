<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanningRecord extends Model
{
    use HasFactory;

    public const CATEGORY_GOVERNMENT = 'government';
    public const CATEGORY_PARTY = 'party';

    protected $fillable = [
        'personal_info_id',
        'position',
        'category',
        'position_title',
        'stage',
        'status',
        'notes',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public static function categories(): array
    {
        return [
            self::CATEGORY_GOVERNMENT => 'Quy hoạch chính quyền',
            self::CATEGORY_PARTY => 'Quy hoạch Đảng',
        ];
    }

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
