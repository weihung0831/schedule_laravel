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
        Schema::connection('sqlsrv')->create('product_lines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('生產線代號');
            $table->string('name')->nullable()->comment('生產線名稱');
            $table->date('created_at')->comment('建立時間');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('product_lines');
    }
};
