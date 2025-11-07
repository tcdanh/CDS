<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'from_period',
        'to_period',
        'coefficient',
        'benefit_percentage',
        'position',
    ];

    protected $casts = [
        'coefficient' => 'float',
        'benefit_percentage' => 'float',
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
