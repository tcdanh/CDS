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
        Schema::create('family_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->cascadeOnDelete();
            $table->text('asset_description')->nullable();
            $table->string('asset_address')->nullable();
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
        Schema::dropIfExists('family_assets');
    }
};
