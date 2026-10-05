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
        if (!Schema::hasColumn('orders', 'confirmed_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('confirmed_at')->nullable()->after('payment_method');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'confirmed_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('confirmed_at');
            });
        }
    }
};
