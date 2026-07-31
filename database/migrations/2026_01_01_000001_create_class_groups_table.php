<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->string('grade_level')->default('3er Semestre');
            $table->string('academic_year')->default('2025-2026');
            $table->integer('total_practices')->default(15);
            $table->integer('total_weeks')->default(5);
            $table->integer('current_week')->default(2);
            $table->string('week_status')->default('normal');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_groups');
    }
};
