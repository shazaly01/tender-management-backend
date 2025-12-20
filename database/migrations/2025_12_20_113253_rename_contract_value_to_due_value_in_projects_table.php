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
        Schema::table('projects', function (Blueprint $table) {
            // تغيير اسم العمود من contract_value إلى due_value
            $table->renameColumn('contract_value', 'due_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // في حال التراجع، يتم إرجاع الاسم القديم
            $table->renameColumn('due_value', 'contract_value');
        });
    }
};
