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
        Schema::create('intellectual_property_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->cascadeOnDelete();
            $table->string('year')->nullable();
            $table->string('name')->nullable();
            $table->string('ip_type')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('notes', 1000)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intellectual_property_records');
    }
};
