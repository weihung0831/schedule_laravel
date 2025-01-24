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
        Schema::connection('sqlsrv')->create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('product_line_code')->nullable()->comment('生產線代號');
            $table->string('packing_date')->nullable()->comment('包裝日期');
            $table->string('plan_begin_date')->nullable()->comment('排程日推算預計開工日');
            $table->string('plan_end_date')->nullable()->comment('排程日推算預計完成日');
            $table->string('customer_code')->nullable()->comment('客戶代號');
            $table->string('order_number')->nullable()->comment('訂單單號');
            $table->string('order_spec')->nullable()->comment('訂單序號');
            $table->string('product_number')->nullable()->comment('品號');
            $table->text('product_name')->nullable()->comment('品名');
            $table->text('product_spec')->nullable()->comment('規格');
            $table->integer('plan_qty')->nullable()->comment('預計產量');
            $table->integer('actual_qty')->nullable()->comment('已生產數量');
            $table->integer('schedule_qty')->nullable()->comment('排產數量');
            $table->integer('manual_work_hours')->nullable()->comment('手動工時');
            $table->integer('erp_work_hours')->nullable()->comment('ERP工時');
            $table->integer('stock')->nullable()->comment('材料庫存');
            $table->string('manufacturing_number')->nullable()->comment('製令單號');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('orders');
    }
};
