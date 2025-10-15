<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
    ];
    /**
     * Automatically use slug in route model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug when creating
        static::creating(function ($news) {
            $news->slug = static::generateUniqueSlug($news->title);
        });

        // Automatically update slug if title changes
        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = static::generateUniqueSlug($news->title, $news->id);
            }
        });
    }
    /**
     * Generate unique slug from title
     */
    protected static function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
                     ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
                     ->exists()) {
            /*$slug = "{$originalSlug}-{$count++}";*/
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
