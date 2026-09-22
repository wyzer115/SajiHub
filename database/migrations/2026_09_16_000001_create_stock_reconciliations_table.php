<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->decimal('opening_stock', 10, 3)->default(0);
            $table->decimal('stock_in', 10, 3)->default(0);
            $table->decimal('stock_out_waste', 10, 3)->default(0);
            $table->decimal('closing_stock', 10, 3)->default(0);
            $table->decimal('use_physical', 10, 3)->default(0);
            $table->decimal('plu_sales', 10, 3)->default(0);
            $table->decimal('diff', 10, 3)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('loss_cost', 12, 2)->default(0);
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reconciliations');
    }
};
