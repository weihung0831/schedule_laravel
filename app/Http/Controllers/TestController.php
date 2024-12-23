<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function getScheduleFakeData()
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'packagingDate' => '2024/03/' . str_pad(($i + 14), 2, '0', STR_PAD_LEFT),
                'customerCode' => 'C' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'orderNumber' => 'SO-20240315-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'orderSeq' => (string)$i,
                'productCode' => 'P' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'productName' => '產品' . chr(64 + $i) . '-規格' . $i,
                'plannedQuantity' => 1000 * $i,
                'producedQuantity' => 0,
                'scheduledQuantity' => 1000 * $i,
                'manualWorkHours' => 8,
                'erpWorkHours' => 10,
                'inventory' => 500 * $i,
                'moNumber' => 'MO-20240315-' . str_pad($i, 3, '0', STR_PAD_LEFT)
            ];
        }

        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function getProductLineFakeData()
    {
        $data = [];
        // 產品線數量
        $productLineCount = 5;
        // 為每條產品線定義固定的機器數量
        $machinesPerLine = [3, 2, 4, 3, 2]; // 每條產品線的機器數量

        for ($i = 1; $i <= $productLineCount; $i++) {
            $machines = [];
            $machineCount = $machinesPerLine[$i - 1];

            for ($j = 1; $j <= $machineCount; $j++) {
                $machineNumber = ($i - 1) * 4 + $j;
                $machines[] = [
                    'machineCode' => 'M' . str_pad($machineNumber, 3, '0', STR_PAD_LEFT),
                ];
            }

            $data[] = [
                'productLineName' => '產品線' . chr(64 + $i),
                'machines' => $machines
            ];
        }

        return response()->json(['status' => 200, 'data' => $data]);
    }
}
