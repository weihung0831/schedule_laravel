<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    /**
     * 批次建立或更新工單資料
     */
    public static function batchCreateOrUpdate(array $ordersData): void
    {
        $orders = Order::all();

        if ($orders->isEmpty()) {
            foreach (array_chunk($ordersData, 100) as $chunk) {
                Order::insert($chunk);
            }
        } else {
            Order::truncate();
            foreach (array_chunk($ordersData, 100) as $chunk) {
                Order::insert($chunk);
            }
        }
    }

    /**
     * 取得工單資料
     */
    public static function getOrders(array $where, array $options)
    {
        $query = Order::query();

        foreach ($where as $field => $value) {
            if (in_array($field, ['begin_date', 'end_date'])) {
                continue;
            }

            if (isset($value)) {
                $query->where($field, $value);
            }
        }

        if (isset($where['begin_date']) && !isset($where['end_date'])) {
            $query->where(function ($query) use ($where) {
                $query->whereDate('packing_date', '<=', $where['begin_date'])
                    ->whereColumn('actual_qty', '<', 'plan_qty');
            });
        }

        if (isset($where['begin_date']) && isset($where['end_date'])) {
            $query->where(function ($query) use ($where) {
                $query->where(function ($q) use ($where) {
                    $q->whereDate('packing_date', '>=', $where['begin_date'])
                        ->whereDate('packing_date', '<=', $where['end_date'])
                        ->whereColumn('actual_qty', '<', 'plan_qty');
                })->orWhere(function ($q) use ($where) {
                    $q->whereDate('packing_date', '<', $where['begin_date'])
                        ->whereColumn('actual_qty', '<', 'plan_qty');
                });
            });
        }

        if (isset($options['sort'])) {
            $query->orderBy($options['sort'], $options['order']);
        }

        $data = $query->get();

        return [
            'count' => $query->count(),
            'data' => $data,
        ];
    }

    /**
     * 取得工單資料
     */
    public static function getOrderById(array $ids)
    {
        return Order::whereIn('id', $ids)->get();
    }
}
