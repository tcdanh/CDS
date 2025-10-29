<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->cascadeOnDelete();
            $table->string('category');
            $table->unsignedInteger('position')->default(0);
            $table->string('timeframe')->nullable();
            $table->string('program_name')->nullable();
            $table->string('certificate')->nullable();
            $table->string('institution')->nullable();
            $table->string('major')->nullable();
            $table->string('training_form')->nullable();
            $table->string('qualification')->nullable();
            $table->string('level')->nullable();
            $table->string('language')->nullable();
            $table->unsignedSmallInteger('year_awarded')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->index(['personal_info_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_records');
    }
};