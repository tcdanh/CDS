<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = [
        'intro_id',
        'name',
        'position',
        'description',
        'image',
    ];

    public function intro()
    {
        return $this->belongsTo(Intro::class);
    }
}
