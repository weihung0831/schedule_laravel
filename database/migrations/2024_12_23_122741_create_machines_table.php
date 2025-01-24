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
        Schema::connection('sqlsrv')->create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('product_line_code')->comment('生產線代號');
            $table->string('name')->comment('機台名稱');
            $table->date('created_at')->comment('建立時間');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('machines');
    }
};
