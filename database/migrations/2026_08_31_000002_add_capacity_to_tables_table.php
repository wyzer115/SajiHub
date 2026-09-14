<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tables', 'capacity')) {
            Schema::table('tables', function (Blueprint $table) {
                $table->integer('capacity')->default(4)->after('table_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tables', 'capacity')) {
            Schema::table('tables', function (Blueprint $table) {
                $table->dropColumn('capacity');
            });
        }
    }
};
