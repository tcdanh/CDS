<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingActivityRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'academic_year',
        'undergraduate_hours',
        'graduate_hours',
        'doctoral_hours',
        'notes',
        'position',
    ];

    protected $casts = [
        'undergraduate_hours' => 'integer',
        'graduate_hours' => 'integer',
        'doctoral_hours' => 'integer',
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
