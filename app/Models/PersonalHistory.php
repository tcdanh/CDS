<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'imprisonment_history',
        'old_regime_roles',
        'foreign_relations',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
