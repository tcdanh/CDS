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
        Schema::create('Detail_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('contract_number')->nullable();
            $table->date('contract_signed_at')->nullable();
            $table->string('contract_storage_path')->nullable();
            $table->decimal('direct_labor_cost', 15, 2)->nullable();
            $table->decimal('material_cost', 15, 2)->nullable();
            $table->decimal('other_cost', 15, 2)->nullable();
            $table->decimal('management_cost', 15, 2)->nullable();
            $table->boolean('is_extended')->default(false);
            $table->text('extension_details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Detail_projects');
    }
};
