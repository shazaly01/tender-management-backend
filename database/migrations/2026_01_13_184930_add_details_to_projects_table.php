<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('contract_number')->nullable()->after('project_owner'); // رقم العقد
            $table->string('region')->nullable()->after('contract_number'); // المنطقة (نص حر)

            // الربط مع جدول خيارات الاحتساب
            $table->foreignId('calculation_option_id')
                  ->nullable()
                  ->after('company_id')
                  ->constrained('calculation_options')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['calculation_option_id']);
            $table->dropColumn(['contract_number', 'region', 'calculation_option_id']);
        });
    }
};
