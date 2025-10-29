<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingRecord extends Model
{
    use HasFactory;

    public const CATEGORY_FORMAL_TRAINING = 'formal_training';
    public const CATEGORY_PROFESSIONAL_DEVELOPMENT = 'professional_development';
    public const CATEGORY_MANAGEMENT_TRAINING = 'management_training';
    public const CATEGORY_POLITICAL_THEORY = 'political_theory';
    public const CATEGORY_NATIONAL_DEFENSE = 'national_defense';
    public const CATEGORY_FOREIGN_LANGUAGE = 'foreign_language';
    public const CATEGORY_INFORMATICS = 'informatics';

    protected $fillable = [
        'personal_info_id',
        'category',
        'position',
        'timeframe',
        'program_name',
        'certificate',
        'institution',
        'major',
        'training_form',
        'qualification',
        'level',
        'language',
        'year_awarded',
        'notes',
    ];

    protected $casts = [
        'year_awarded' => 'integer',
        'position' => 'integer',
    ];

    public function personalInfo(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
