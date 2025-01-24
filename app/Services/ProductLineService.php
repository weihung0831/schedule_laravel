<?php

namespace App\Services;

use App\Models\ProductLine;

class ProductLineService
{
    /**
     * 批次建立或更新生產線資料
     */
    public static function batchCreateOrUpdate(array $productLinesData): void
    {
        $productLines = ProductLine::all();

        if ($productLines->isEmpty()) {
            ProductLine::insert($productLinesData);
        } else {
            ProductLine::truncate();
            ProductLine::insert($productLinesData);
        }
    }

    /**
     * 取得生產線資料
     */
    public static function getProductLines()
    {
        $data = ProductLine::all();

        return [
            'count' => count($data),
            'data' => $data,
        ];
    }
}
