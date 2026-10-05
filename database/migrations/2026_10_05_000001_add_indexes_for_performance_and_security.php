<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['branch_id', 'created_at'], 'idx_orders_branch_created');
            $table->index(['branch_id', 'order_status'], 'idx_orders_branch_status');
            $table->index(['branch_id', 'payment_status'], 'idx_orders_branch_payment');
            $table->index('confirmed_at', 'idx_orders_confirmed_at');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['branch_id', 'created_at'], 'idx_tx_branch_created');
            $table->index(['branch_id', 'status'], 'idx_tx_branch_status');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->index(['branch_id', 'table_number'], 'idx_tables_branch_number');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->index(['branch_id', 'status'], 'idx_menus_branch_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_branch_created');
            $table->dropIndex('idx_orders_branch_status');
            $table->dropIndex('idx_orders_branch_payment');
            $table->dropIndex('idx_orders_confirmed_at');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_tx_branch_created');
            $table->dropIndex('idx_tx_branch_status');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->dropIndex('idx_tables_branch_number');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropIndex('idx_menus_branch_status');
        });
    }
};
