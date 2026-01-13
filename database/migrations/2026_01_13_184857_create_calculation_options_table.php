<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_options', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الخيار (مثلاً: الخيار 1، الخيار 2...)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_options');
    }
};
