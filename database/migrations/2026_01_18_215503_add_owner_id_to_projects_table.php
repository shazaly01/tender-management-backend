<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // نضعه بعد project_owner ليسهل المقارنة
            $table->foreignId('owner_id')
                  ->nullable() // مهم جداً أن يكون فارغاً الآن لتجنب الأخطاء في البيانات القديمة
                  ->after('project_owner')
                  ->constrained('owners')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
};
