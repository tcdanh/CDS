<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intro extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_description',
        'image',
        'vision',
        'mission',
        'goals',
        'user_id',
    ];
    
    // Người tạo intro
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Các thành viên (structures)
    public function structures()
    {
        return $this->hasMany(Structure::class);
    }

    // Các thành tựu
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    // Các đối tác
    public function partners()
    {
        return $this->hasMany(Partner::class);
    }
}
