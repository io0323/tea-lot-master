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
        Schema::create('tea_lots', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code')->unique();
            $table->string('tea_type');
            $table->string('origin');
            $table->decimal('moisture', 5, 2); /* 含水率（%） */
            $table->unsignedTinyInteger('aroma_score'); /* 香りスコア（0-100） */
            $table->unsignedTinyInteger('color_score'); /* 色スコア（0-100） */
            $table->date('inspected_at');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('inspector_id')->constrained('inspectors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tea_lots');
    }
};
