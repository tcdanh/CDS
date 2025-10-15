<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignLanguage extends Model
{
    protected $fillable = [
        'profile_id',
        'language_name',
        'listening',
        'speaking',
        'writing',
        'reading',
    ];

    public function profile()
    {
        return $this->belongsTo(ScientificProfile::class, 'profile_id');
    }
}
