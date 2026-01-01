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
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // الطالبة
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // السؤال
            $table->boolean('is_correct')->default(false); // نتيجة المحاولة
            $table->unsignedInteger('tries')->default(1); // رقم المحاولة داخل نفس التفاعل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
