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
        Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('volunteer_id');
        $table->unsignedBigInteger('organization_id');
        $table->tinyInteger('rating'); // من 1 إلى 5
        $table->text('comment')->nullable(); // التعليق اختياري
        $table->timestamps();

        $table->foreign('volunteer_id')->references('volunteer_id')->on('volunteers')->onDelete('cascade');
        $table->foreign('organization_id')->references('organization_id')->on('organizations')->onDelete('cascade');
        $table->unique(['volunteer_id', 'organization_id']); // متطوع يقيّم المؤسسة مرة واحدة فقط
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
