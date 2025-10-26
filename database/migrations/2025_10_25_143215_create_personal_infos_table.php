<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('alternate_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            //$table->string('gender')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('hometown')->nullable();
            $table->string('residence')->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('cccd_number')->nullable();
            $table->date('cccd_issued_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('religion')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('health_insurance_number')->nullable();
            $table->string('social_insurance_number')->nullable();
            $table->date('employment_start_date')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('main_job_title')->nullable();
            $table->string('professional_title')->nullable();
            $table->string('expertise')->nullable();
            $table->string('previous_job')->nullable();
            $table->date('youth_union_joined_at')->nullable();
            $table->date('trade_union_joined_at')->nullable();
            $table->date('communist_party_joined_at')->nullable();
            $table->date('army_enlisted_at')->nullable();
            $table->date('army_discharged_at')->nullable();
            $table->string('highest_army_rank')->nullable();
            $table->string('general_education_level')->nullable();
            $table->string('highest_academic_level')->nullable();
            $table->integer('highest_academic_year')->nullable();
            $table->string('graduation_major')->nullable();
            $table->string('state_honors')->nullable();
            $table->integer('state_honors_year')->nullable();
            $table->string('academic_title')->nullable();
            $table->integer('academic_title_year')->nullable();
            $table->string('professor_council')->nullable();
            $table->string('health_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('teaching_field')->nullable();
            $table->string('research_field')->nullable();
            $table->timestamps();

            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
