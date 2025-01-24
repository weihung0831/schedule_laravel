<?php

namespace App\Services\Erp;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\ERP\Interfaces\ErpConnection;
use App\Services\ERP\Interfaces\OrderSync;
use App\Models\Schedule;

class OrderProvider implements OrderSync
{
    /**
     * 取得工單資料
     *
     * @throws Exception
     */
    public function getOrders(): array
    {
        ini_set('memory_limit', '1024M');

        $connection = DB::connection(ErpConnection::ERP_CONNECTION);

        try {
            $connection->beginTransaction();

            $result = $connection
                ->table(ErpConnection::ERP_ORDER_TABLE)
                ->select([
                    '排程日期',
                    '排程日推算預計開工日',
                    '排程日推算預計完成日',
                    '客戶代號',
                    '訂單單號',
                    '訂單品號',
                    '訂單品名',
                    '訂單規格',
                    '預計產量',
                    '製令單號',
                    '加工對象',
                    '材料庫存',
                ])
                ->orderBy('製令單號')
                ->cursor()
                ->map(fn($record) => $this->transformToOrder($record))
                ->collect()
                ->all();

            $connection->commit();
            return $result;
        } catch (Exception $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    /**
     * 轉換為工單資料格式
     */
    private function transformToOrder(object $erpData): array
    {
        return [
            'packing_date' => str_replace('/', '', $erpData->排程日期),
            'plan_begin_date' => str_replace('/', '', $erpData->排程日推算預計開工日),
            'plan_end_date' => str_replace('/', '', $erpData->排程日推算預計完成日),
            'customer_code' => $erpData->客戶代號,
            'order_number' => ($parts = explode('-', $erpData->訂單單號))[1],
            'order_spec' => $parts[2],
            'product_number' => $erpData->訂單品號,
            'product_name' => $erpData->訂單品名,
            'product_spec' => $erpData->訂單規格,
            'plan_qty' => $erpData->預計產量,
            // TODO 到時抓取報工系統統的欄位
            'actual_qty' => 0,
            // TODO 手動工時、ERP工時先給0, 手動工時是抓erp工時
            'erp_work_hours' => 0,
            'manufacturing_number' => $erpData->製令單號,
            'product_line_code' => preg_replace('/^([A-Z0-9]+).*$/', '$1', $erpData->加工對象),
            'stock' => $erpData->材料庫存,
        ];
    }
}
