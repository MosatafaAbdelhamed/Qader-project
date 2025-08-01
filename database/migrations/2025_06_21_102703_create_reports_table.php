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
        Schema::create('reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('volunteer_id')->constrained('volunteers')->onDelete('cascade');
    $table->string('title');
    $table->text('description');
    $table->string('location');
    $table->string('phone');
    $table->enum('urgency', ['high', 'medium', 'low']);
    $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
