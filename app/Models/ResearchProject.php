<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchProject extends Model
{
    protected $fillable = [
        'profile_id',
        'title',
        'code',
        'level',
        'duration',
        'budget_million_vnd',
        'role',
        'acceptance_date',
        'result',
    ];

    protected $casts = [
        'acceptance_date' => 'date',
    ];

    public function profile()
    {
        return $this->belongsTo(ScientificProfile::class, 'profile_id');
    }
}
