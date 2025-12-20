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
        Schema::table('documents', function (Blueprint $table) {
            // تغيير النوع ليتطابق مع id في جداول companies و projects
            // ملاحظة: تأكد من تثبيت مكتبة doctrine/dbal (composer require doctrine/dbal)
            $table->unsignedBigInteger('documentable_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->decimal('documentable_id', 18, 0)->change();
        });
    }
};
