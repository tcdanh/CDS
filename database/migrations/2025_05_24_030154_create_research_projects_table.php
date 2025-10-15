<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('research_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');

            $table->string('title');
            $table->string('code')->nullable(); // mã số đề tài
            $table->string('level')->nullable(); // cấp quản lý
            $table->string('duration')->nullable(); // thời gian thực hiện
            $table->integer('budget_million_vnd')->nullable(); // kinh phí (triệu đồng)
            $table->string('role')->nullable(); // chủ nhiệm / tham gia
            $table->date('acceptance_date')->nullable(); // ngày nghiệm thu
            $table->text('result')->nullable();

            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('scientific_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_projects');
    }
};
