<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchProjectRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'from_period',
        'to_period',
        'project_name',
        'project_type',
        'role',
        'budget_million_vnd',
        'status',
        'notes',
        'position',
    ];

    protected $casts = [
        'budget_million_vnd' => 'integer',
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
