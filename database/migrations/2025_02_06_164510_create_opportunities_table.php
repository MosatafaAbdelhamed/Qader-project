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
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->foreignId('category_id')->constrained('categories'); // ربط الفرصة بالفئة
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
