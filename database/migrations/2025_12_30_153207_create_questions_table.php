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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text'); // نص السؤال
            $table->string('type'); // نوع السؤال: true_false, multiple_choice, fill_blank, fix_answer
            $table->string('category')->nullable(); // تصنيف (Present Simple... اختياري)
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade'); // الصف المرتبط
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
