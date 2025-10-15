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
        Schema::create('banner_articles', function (Blueprint $table) {
            $table->id();
            $table->string('tittle', 255); // max 30 từ (giới hạn ký tự bằng maxlength HTML/validation)
            $table->text('mota_en');       // mô tả tiếng Anh (100 từ)
            $table->text('mota_vn');       // mô tả tiếng Việt (100 từ)
            $table->string('hinhanh');     // tên file hình ảnh (jpg, png), lưu ở public/images/banner_article
            $table->unsignedBigInteger('id_user'); // user tạo
            $table->string('link')->nullable();    // link đến thông tin chi tiết
            $table->timestamps();
        
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_articles');
    }
};
