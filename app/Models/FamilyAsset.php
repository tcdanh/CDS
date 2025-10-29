<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FamilyAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'asset_description',
        'asset_address',
        'notes',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
