<?php

namespace App\Http\Controllers;

use App\Services\ERP\Interfaces\MachineSync;
use App\Services\ERP\Interfaces\OrderSync;
use App\Services\ERP\Interfaces\ProductionLineSync;
use App\Services\MachineService;
use App\Services\OrderService;
use App\Services\ProductLineService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * 資料庫管理控制器
 */
class DBAController extends Controller
{
    private ProductionLineSync $productionLineSync;
    private MachineSync $machineSync;
    private OrderSync $orderSync;

    /**
     * 建構子
     */
    public function __construct(ProductionLineSync $productionLineSync, MachineSync $machineSync, OrderSync $orderSync)
    {
        $this->productionLineSync = $productionLineSync;
        $this->machineSync = $machineSync;
        $this->orderSync = $orderSync;
    }

    /**
     * 同步 ERP 資料
     */
    public function syncErpData(): JsonResponse
    {
        try {
            Log::info('開始同步 ERP 資料');
            $productLines = $this->productionLineSync->getProductLines(); // 取得生產線資料
            $machines = $this->machineSync->getMachines(); // 取得機台資料
            $orders = $this->orderSync->getOrders(); // 取得工單資料

            $this->processProductLines($productLines); // 處理生產線資料
            $this->processMachines($machines); // 處理機台資料
            $this->processOrders($orders); // 處理工單資料

            Log::info('ERP 資料同步成功');

            return $this->successResponse('ERP 資料同步成功');
        } catch (Exception $e) {
            Log::error($e);

            return $this->errorResponse($e);
        }
    }

    /**
     * 同步生產線資料
     */
    public function syncProductLineData(): JsonResponse
    {
        try {
            $productLines = $this->productionLineSync->getProductLines();
            $this->processProductLines($productLines);

            Log::info('生產線資料同步成功');

            return $this->successResponse('生產線資料同步成功');
        } catch (Exception $e) {
            Log::error($e);

            return $this->errorResponse($e);
        }
    }

    /**
     * 處理生產線資料
     */
    private function processProductLines(array $productLines): void
    {
        ProductLineService::batchCreateOrUpdate($productLines);
    }

    /**
     * 同步機台資料
     */
    public function syncMachineData(): JsonResponse
    {
        try {
            $machines = $this->machineSync->getMachines();
            $this->processMachines($machines);

            Log::info('機台資料同步成功');

            return $this->successResponse('機台資料同步成功');
        } catch (Exception $e) {
            Log::error($e);

            return $this->errorResponse($e);
        }
    }

    /**
     * 處理機台資料
     */
    private function processMachines(array $machines): void
    {
        MachineService::batchCreateOrUpdate($machines);
    }

    /**
     * 同步工單資料
     */
    public function syncOrderData(): JsonResponse
    {
        try {
            $orders = $this->orderSync->getOrders();
            $this->processOrders($orders);

            Log::info('工單資料同步成功');

            return $this->successResponse('工單資料同步成功');
        } catch (Exception $e) {
            Log::error($e);

            return $this->errorResponse($e);
        }
    }

    /**
     * 處理工單資料
     */
    private function processOrders(array $orders): void
    {
        OrderService::batchCreateOrUpdate($orders);
    }

    /**
     * 回傳成功
     */
    private function successResponse(string $message): JsonResponse
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
        ]);
    }

    /**
     * 回傳錯誤
     */
    private function errorResponse(Exception $exception): JsonResponse
    {
        return response()->json([
            'status' => 500,
            'message' => $exception->getMessage()
        ], 500);
    }
}
