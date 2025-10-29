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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->cascadeOnDelete();
            $table->string('side', 20)->default('self');
            $table->string('relationship')->nullable();
            $table->string('full_name');
            $table->unsignedSmallInteger('birth_year')->nullable();
            $table->string('hometown')->nullable();
            $table->string('residence')->nullable();
            $table->string('occupation')->nullable();
            $table->string('workplace')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
