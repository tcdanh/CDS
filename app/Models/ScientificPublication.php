<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScientificPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'year',
        'title',
        'role',
        'publication_type',
        'publisher',
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
