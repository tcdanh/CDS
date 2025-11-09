<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisionActivityRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_info_id',
        'level',
        'student_name',
        'topic',
        'year',
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
