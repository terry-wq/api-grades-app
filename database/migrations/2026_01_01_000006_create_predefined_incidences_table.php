<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predefined_incidences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['positive', 'negative'])->default('positive');
            $table->float('points')->default(1.0);
            $table->string('category')->default('General');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predefined_incidences');
    }
};
