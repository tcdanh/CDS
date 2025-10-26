<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScientificProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'place_of_birth',
        'gender',
        'code_employee',
        'unit',
        'department',
        'position',
        'degree',
        'degree_year',
        'title',
        'title_year',
        'contact_office',
        'contact_home',
        'phone_office',
        'phone_home',
        'email_office',
        'email_home',
        'avatar_path'
    ];
    protected $casts = [
        'birth_date' => 'date',
        'degree_year' => 'integer',
        'title_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foreignLanguages()
    {
        return $this->hasMany(ForeignLanguage::class, 'profile_id');
    }

    public function educationalBackgrounds()
    {
        return $this->hasMany(EducationalBackground::class, 'profile_id');
    }

    public function researchProjects()
    {
        return $this->hasMany(ResearchProject::class, 'profile_id');
    }
}
