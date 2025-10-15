<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalBackground extends Model
{
    protected $fillable = [
        'profile_id',
        'level',
        'time_range',
        'institution',
        'major',
        'thesis_title',
    ];

    public function profile()
    {
        return $this->belongsTo(ScientificProfile::class, 'profile_id');
    }
}
