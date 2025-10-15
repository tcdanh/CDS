<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scientific_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // liên kết với users

            $table->string('full_name');
            $table->date('birth_date')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('code_employee')->nullable();

            $table->string('unit')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();

            $table->string('degree')->nullable();
            $table->year('degree_year')->nullable();
            $table->string('title')->nullable();
            $table->year('title_year')->nullable();

            $table->string('contact_office')->nullable();
            $table->string('contact_home')->nullable();
            $table->string('phone_office')->nullable();
            $table->string('phone_home')->nullable();
            $table->string('email_office')->nullable();
            $table->string('email_home')->nullable();
            $table->string('avatar_path')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scientific_profiles');
    }
};
