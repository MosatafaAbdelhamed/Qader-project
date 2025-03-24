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
        if (!Schema::hasTable('opportunities')) {
            Schema::create('opportunities', function (Blueprint $table) {
                $table->id('opportunity_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('start')->nullable();
                $table->date('end')->nullable();
                $table->unsignedBigInteger('category_id');
                $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
                $table->unsignedBigInteger('organization_id');
                $table->foreign('organization_id')->references('organization_id')->on('organizations')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
