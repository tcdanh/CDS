<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSchedule extends Model
{
    use HasFactory;

    public const TIME_OF_DAY_MORNING = 'morning';
    public const TIME_OF_DAY_AFTERNOON = 'afternoon';

    protected $fillable = [
        'user_id',
        'scheduled_date',
        'time_of_day',
        'content',
        'time_range',
        'location',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
