<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'tittle',
        'mota_en',
        'mota_vn',
        'hinhanh',
        'id_user',
        'link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
}
