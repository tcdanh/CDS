<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('educational_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id'); // FK tới scientific_profiles

            $table->string('level'); // Đại học, Thạc sĩ, Tiến sĩ, ...
            $table->string('time_range')->nullable(); // Ví dụ: 2008-2012
            $table->string('institution')->nullable(); // Nơi đào tạo
            $table->string('major')->nullable(); // Chuyên ngành
            $table->string('thesis_title')->nullable(); // Tên luận văn / luận án

            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('scientific_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educational_backgrounds');
    }
};
