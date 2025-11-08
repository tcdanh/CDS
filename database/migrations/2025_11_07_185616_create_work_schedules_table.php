<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->enum('time_of_day', ['morning', 'afternoon']);
            $table->text('content');
            $table->string('time_range')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['scheduled_date', 'time_of_day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
