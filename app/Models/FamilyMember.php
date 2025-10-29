<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    use HasFactory;

    public const SIDE_SELF = 'self';
    public const SIDE_SPOUSE = 'spouse';

    protected $fillable = [
        'personal_info_id',
        'side',
        'relationship',
        'full_name',
        'birth_year',
        'hometown',
        'residence',
        'occupation',
        'workplace',
        'notes',
        'position',
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
