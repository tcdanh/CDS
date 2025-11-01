<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name_vi');
            $table->string('name_en');
            $table->string('industry_group')->nullable();
            $table->string('research_type')->nullable();
            $table->string('implementation_time')->nullable();
            $table->foreignId('principal_investigator_id')->nullable()->constrained('personal_infos')->nullOnDelete();
            $table->string('science_secretary')->nullable();
            $table->decimal('total_budget', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
