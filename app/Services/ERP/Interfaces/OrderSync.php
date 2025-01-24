<?php

namespace App\Services\ERP\Interfaces;

interface OrderSync
{
    /**
     * 取得工單資料
     */
    public function getOrders(): array;
}
