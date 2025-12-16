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
        Schema::table('companies', function (Blueprint $table) {
            // إضافة عمود رقم الرخصة بعد عمود الرقم الضريبي
            // nullable() للسماح بأن يكون فارغاً
            $table->string('license_number')->nullable()->after('tax_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // حذف العمود عند التراجع عن الـ migration
            $table->dropColumn('license_number');
        });
    }
};
