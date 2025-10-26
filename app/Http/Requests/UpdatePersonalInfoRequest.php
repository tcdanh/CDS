<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Chỉ cho phép khi có người dùng đăng nhập
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'alternate_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],

            // ✅ Enum cho giới tính (male/female)
            'gender' => ['nullable', Rule::in(['male', 'female'])],

            'birth_place' => ['nullable', 'string', 'max:255'],
            'hometown' => ['nullable', 'string', 'max:255'],
            'residence' => ['nullable', 'string', 'max:255'],
            'cccd_number' => ['nullable', 'string', 'max:50'],
            'cccd_issued_date' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'ethnicity' => ['nullable', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'tax_code' => ['nullable', 'string', 'max:50'],
            'health_insurance_number' => ['nullable', 'string', 'max:50'],
            'social_insurance_number' => ['nullable', 'string', 'max:50'],
            'employment_start_date' => ['nullable', 'date'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'contract_type' => ['nullable', 'string', 'max:100'],
            'main_job_title' => ['nullable', 'string', 'max:255'],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'previous_job' => ['nullable', 'string', 'max:255'],
            'youth_union_joined_at' => ['nullable', 'date'],
            'trade_union_joined_at' => ['nullable', 'date'],
            'communist_party_joined_at' => ['nullable', 'date'],
            'army_enlisted_at' => ['nullable', 'date'],
            'army_discharged_at' => ['nullable', 'date'],
            'highest_army_rank' => ['nullable', 'string', 'max:255'],
            'general_education_level' => ['nullable', 'string', 'max:255'],
            'highest_academic_level' => ['nullable', 'string', 'max:255'],
            'highest_academic_year' => ['nullable', 'digits:4'],
            'graduation_major' => ['nullable', 'string', 'max:255'],
            'state_honors' => ['nullable', 'string', 'max:255'],
            'state_honors_year' => ['nullable', 'digits:4'],
            'academic_title' => ['nullable', 'string', 'max:255'],
            'academic_title_year' => ['nullable', 'digits:4'],
            'professor_council' => ['nullable', 'string', 'max:255'],
            'health_status' => ['nullable', 'string', 'max:255'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'height' => ['nullable', 'string', 'max:10'],
            'weight' => ['nullable', 'string', 'max:10'],
            'teaching_field' => ['nullable', 'string', 'max:255'],
            'research_field' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'], // max = 2MB
        ];
    }
}
