<?php

namespace App\Http\Controllers;

use App\Services\ProductLineService;

class ProductLineController extends Controller
{
    /**
     * 取得產線資料
     */
    public function get()
    {
        try {
            return ProductLineService::getProductLines();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
