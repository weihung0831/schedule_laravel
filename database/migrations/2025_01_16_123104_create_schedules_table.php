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
        Schema::connection('sqlsrv')->create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->comment('訂單ID');
            $table->string('machine_name')->nullable()->comment('機台名稱');
            $table->integer('changed_schedule_qty')->default(0)->comment('更改後排程數量');
            $table->integer('changed_manual_work_hours')->default(0)->comment('更改後手動工時');
            $table->date('schedule_date')->nullable()->comment('排程日期');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('schedules');
    }
};
