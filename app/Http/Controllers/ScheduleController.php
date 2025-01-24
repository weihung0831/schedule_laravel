<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScheduleService;
use App\Services\OrderService;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public static function create(Request $request)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.order_id' => 'required|integer',
            'schedules.*.machine_name' => 'required|string',
            'schedules.*.schedule_date' => 'required|date',
            'schedules.*.changed_schedule_qty' => 'required|integer',
            'schedules.*.changed_manual_work_hours' => 'required|integer',
        ]);

        try {
            foreach ($validated['schedules'] as $scheduleData) {
                if (Schedule::where('order_id', $scheduleData['order_id'])
                    ->where('schedule_date', $scheduleData['schedule_date'])
                    ->exists()) {
                    // 累加當天現有排程
                    $schedule = Schedule::where('order_id', $scheduleData['order_id'])
                        ->where('schedule_date', $scheduleData['schedule_date'])
                        ->first();
                    $schedule->changed_schedule_qty += $scheduleData['changed_schedule_qty'];
                    $schedule->changed_manual_work_hours += $scheduleData['changed_manual_work_hours'];
                    $schedule->save();
                } else {
                    ScheduleService::createSchedule([$scheduleData]);
                }
            }

            return response()->json(['message' => '排程建立成功'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function get(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'machine_name' => 'nullable|string',
        ]);

        try {
            $where = [];

            if (isset($validated['date'])) {
                $where['schedule_date'] = $validated['date'];
            }

            if (isset($validated['machine_name'])) {
                $where['machine_name'] = $validated['machine_name'];
            }

            $data = ScheduleService::getSchedule($where);
            $order_ids = [];

            foreach ($data as $item) {
                $order_ids[] = $item->order_id;
            }

            $orders = OrderService::getOrderById($order_ids);
            $ordersCollection = collect($orders);

            $result = $data->map(function ($item) use ($ordersCollection) {
                $item->packing_date = $ordersCollection->where('id', $item->order_id)->first()->packing_date;
                $item->customer_code = $ordersCollection->where('id', $item->order_id)->first()->customer_code;
                $item->order_number = $ordersCollection->where('id', $item->order_id)->first()->order_number;
                $item->order_spec = $ordersCollection->where('id', $item->order_id)->first()->order_spec;
                $item->product_number = $ordersCollection->where('id', $item->order_id)->first()->product_number;
                $item->product_name = $ordersCollection->where('id', $item->order_id)->first()->product_name;
                $item->product_spec = $ordersCollection->where('id', $item->order_id)->first()->product_spec;
                $item->plan_qty = $ordersCollection->where('id', $item->order_id)->first()->plan_qty;
                $item->actual_qty = $ordersCollection->where('id', $item->order_id)->first()->actual_qty;
                $item->manual_work_hours = $ordersCollection->where('id', $item->order_id)->first()->manual_work_hours;
                $item->erp_work_hours = $ordersCollection->where('id', $item->order_id)->first()->erp_work_hours;
                $item->total_work_hours = 0;
                $item->stock = $ordersCollection->where('id', $item->order_id)->first()->stock;
                $item->manufacturing_number = $ordersCollection->where('id', $item->order_id)->first()->manufacturing_number;

                unset($item->order_id);

                return $item;
            });

            return response()->json(['data' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function update(Request $request)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.id' => 'required|integer',
            'schedules.*.machine_name' => 'required|string',
            'schedules.*.schedule_date' => 'required|date',
        ]);

        try {
            foreach ($validated['schedules'] as $schedule) {
                ScheduleService::updateSchedule($schedule['id'], $schedule['machine_name'], $schedule['schedule_date']);
            }
            return response()->json(['message' => '排程更新成功'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getAchievementRate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'machine_name' => 'required|string',
        ]);

        try {
            $data = ScheduleService::getAchievementRate($validated['date'], $validated['machine_name']);
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
