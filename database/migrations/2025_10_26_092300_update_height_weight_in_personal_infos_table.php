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
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->string('height', 50)->nullable()->change();
            $table->string('weight', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->string('height', 255)->nullable()->change();
            $table->string('weight', 255)->nullable()->change();
        });
    }
};
