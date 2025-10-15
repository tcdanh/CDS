<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('foreign_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id'); // FK tới scientific_profiles

            $table->string('language_name'); // Ví dụ: Tiếng Anh
            $table->enum('listening', ['Tốt', 'Khá', 'TB'])->nullable();
            $table->enum('speaking', ['Tốt', 'Khá', 'TB'])->nullable();
            $table->enum('writing', ['Tốt', 'Khá', 'TB'])->nullable();
            $table->enum('reading', ['Tốt', 'Khá', 'TB'])->nullable();

            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('scientific_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foreign_languages');
    }
};
