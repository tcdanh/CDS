<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->cascadeOnDelete();
            $table->text('imprisonment_history')->nullable();
            $table->text('old_regime_roles')->nullable();
            $table->text('foreign_relations')->nullable();
            $table->timestamps();

            $table->unique('personal_info_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_histories');
    }
};
