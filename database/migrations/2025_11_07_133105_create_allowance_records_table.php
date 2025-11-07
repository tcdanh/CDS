<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allowance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->cascadeOnDelete();
            $table->string('from_period', 20)->nullable();
            $table->string('to_period', 20)->nullable();
            $table->string('allowance_type')->nullable();
            $table->decimal('salary_percentage', 5, 2)->nullable();
            $table->decimal('coefficient', 8, 2)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allowance_records');
    }
};
