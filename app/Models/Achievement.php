<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'intro_id',
        'type',
        'description',
        'thoigian', // thêm vào đây
    ];

    public function intro()
    {
        return $this->belongsTo(Intro::class);
    }
}
