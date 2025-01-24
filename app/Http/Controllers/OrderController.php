<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\Schedule;

class OrderController extends Controller
{
    /**
     * 取得訂單資料
     */
    public function get(Request $request)
    {
        $validated = $request->validate([
            'product_line_code' => 'required|string',
            'customer_code' => 'nullable|string',
            'order_number' => 'nullable|string',
            'sort' => 'nullable|string',
            'order' => 'nullable|string',
            'begin_date' => 'nullable|string',
            'end_date' => 'nullable|date',
            'plan_begin_date' => 'nullable|date',
            'plan_end_date' => 'nullable|date',
        ]);

        try {
            $where = [
                'product_line_code' => $validated['product_line_code'],
            ];

            $options = [
                'order' => 'asc',
                'sort' => 'packing_date',
            ];

            if (isset($validated['customer_code'])) {
                $where['customer_code'] = $validated['customer_code'];
            }

            if (isset($validated['order_number'])) {
                $parts = explode('-', $validated['order_number']);
                $where['order_number'] = $parts[0];
                if (isset($parts[1])) {
                    $where['order_spec'] = $parts[1];
                }
            }

            if (isset($validated['begin_date'])) {
                $where['begin_date'] = $validated['begin_date'];
            }

            if (isset($validated['end_date'])) {
                $where['end_date'] = $validated['end_date'];
            }

            if (isset($validated['plan_begin_date'])) {
                $where['plan_begin_date'] = $validated['plan_begin_date'];
            }

            if (isset($validated['plan_end_date'])) {
                $where['plan_end_date'] = $validated['plan_end_date'];
            }

            $result = OrderService::getOrders($where, $options);

            foreach ($result['data'] as $order) {
                $order->schedule_qty = Schedule::where('order_id', $order->id)->sum('changed_schedule_qty');
                $order->manual_work_hours = Schedule::where('order_id', $order->id)->sum('changed_manual_work_hours');
            }

            return json_encode($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
