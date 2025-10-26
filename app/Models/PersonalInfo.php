<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'alternate_name',
        'birth_date',
        'gender',
        'birth_place',
        'hometown',
        'residence',
        'avatar_path',
        'cccd_number',
        'cccd_issued_date',
        'phone_number',
        'ethnicity',
        'religion',
        'email',
        'tax_code',
        'health_insurance_number',
        'social_insurance_number',
        'employment_start_date',
        'organization_name',
        'contract_type',
        'main_job_title',
        'professional_title',
        'expertise',
        'previous_job',
        'youth_union_joined_at',
        'trade_union_joined_at',
        'communist_party_joined_at',
        'army_enlisted_at',
        'army_discharged_at',
        'highest_army_rank',
        'general_education_level',
        'highest_academic_level',
        'highest_academic_year',
        'graduation_major',
        'state_honors',
        'state_honors_year',
        'academic_title',
        'academic_title_year',
        'professor_council',
        'health_status',
        'blood_group',
        'height',
        'weight',
        'teaching_field',
        'research_field',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'cccd_issued_date' => 'date',
        'employment_start_date' => 'date',
        'youth_union_joined_at' => 'date',
        'trade_union_joined_at' => 'date',
        'communist_party_joined_at' => 'date',
        'army_enlisted_at' => 'date',
        'army_discharged_at' => 'date',
        'highest_academic_year' => 'integer',
        'state_honors_year' => 'integer',
        'academic_title_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
