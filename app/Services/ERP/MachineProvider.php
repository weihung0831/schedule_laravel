<?php

namespace App\Services\Erp;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\ProductLine;
use App\Services\ERP\Interfaces\ErpConnection;
use App\Services\ERP\Interfaces\MachineSync;

class MachineProvider implements MachineSync
{
    /**
     * 取得機台資料
     */
    public function getMachines(): array
    {
        return $this->fetchFromErp(ErpConnection::ERP_MACHINE_TABLE)
            ->map(fn($item) => $this->transformToMachine($item))
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
     * 轉換為機台資料格式
     */
    private function transformToMachine(object $erpData): array
    {
        return [
            'name' => trim($erpData->MX001),
            'product_line_code' => trim($erpData->MX002),
            'created_at' => $erpData->CREATE_DATE,
        ];
    }
}
