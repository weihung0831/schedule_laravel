<?php

namespace App\Services\Erp;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\ERP\Interfaces\ErpConnection;
use App\Services\ERP\Interfaces\ProductionLineSync;

class ProductLineProvider implements ProductionLineSync
{
    /**
     * 取得生產線資料
     */
    public function getProductLines(): array
    {
        return $this->fetchFromErp(ErpConnection::ERP_PRODUCT_LINE_TABLE)
            ->map(fn($item) => $this->transformToProductLine($item))
            ->all();
    }

    /**
     * 從 ERP 資料庫獲取資料
     */
    private function fetchFromErp(string $table): Collection
    {
        return DB::connection(ErpConnection::ERP_CONNECTION)
            ->table($table)
            ->get();
    }

    /**
     * 轉換為生產線資料格式
     */
    private function transformToProductLine(object $erpData): array
    {
        return [
            'code' => trim($erpData->MD001),
            'name' => $erpData->MD002,
            'created_at' => $erpData->CREATE_DATE,
        ];
    }
}
