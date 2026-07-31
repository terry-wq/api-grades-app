<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('class_group_id')->constrained('class_groups')->onDelete('cascade');
            $table->string('name');
            $table->string('avatar')->default('https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250');
            $table->enum('gender', ['M', 'F', 'O'])->default('M');
            $table->float('base_grade')->default(8.0);
            $table->float('exam_grade')->default(0.0);
            $table->json('evaluation_grades')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
