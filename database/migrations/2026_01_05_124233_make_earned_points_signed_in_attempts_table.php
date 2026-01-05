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
        Schema::table('attempts', function (Blueprint $table) {
            // غيّر نوع العمود ليكون signed ويقبل القيم السالبة
            $table->integer('earned_points')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            // ارجعه كما كان (لو كان unsignedInteger مثلًا)
            $table->unsignedInteger('earned_points')->default(0)->change();
        });
    }
};
