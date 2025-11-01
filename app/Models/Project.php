<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_vi',
        'name_en',
        'industry_group',
        'research_type',
        'implementation_time',
        'principal_investigator_id',
        'science_secretary',
        'total_budget',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_budget' => 'decimal:2',
    ];

    public function principalInvestigator(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class, 'principal_investigator_id');
    }
}
