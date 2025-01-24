<?php

namespace App\Services\ERP\Interfaces;

interface ProductionLineSync
{
    /**
     * 取得生產線資料
     */
    public function getProductLines(): array;
}
