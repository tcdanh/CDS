<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllowanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'from_period',
        'to_period',
        'allowance_type',
        'salary_percentage',
        'coefficient',
        'amount',
        'position',
    ];

    protected $casts = [
        'salary_percentage' => 'float',
        'coefficient' => 'float',
        'amount' => 'float',
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
