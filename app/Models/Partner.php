<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'intro_id',
        'name',
        'logo',
    ];

    public function intro()
    {
        return $this->belongsTo(Intro::class);
    }
}
