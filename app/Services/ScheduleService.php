<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * 建立排程資料
     */
    public static function createSchedule($schedules)
    {
        try {
            Schedule::insert(array_map(function ($schedule) {
                return [
                    'order_id' => $schedule['order_id'],
                    'machine_name' => $schedule['machine_name'],
                    'schedule_date' => $schedule['schedule_date'],
                    'changed_schedule_qty' => $schedule['changed_schedule_qty'],
                    'changed_manual_work_hours' => $schedule['changed_manual_work_hours']
                ];
            }, $schedules));

            // 更新工單資料
            foreach ($schedules as $schedule) {
                Order::where('id', $schedule['order_id'])->update([
                    'schedule_qty' => Schedule::where('order_id', $schedule['order_id'])->sum('changed_schedule_qty'),
                    'manual_work_hours' => Schedule::where('order_id', $schedule['order_id'])->sum('changed_manual_work_hours')
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // 取得排程資料
    public static function getSchedule($where)
    {
        $query = Schedule::query();

        foreach ($where as $field => $value) {
            if (isset($value)) {
                $query->where($field, $value);
            }
        }

        return $query->get();
    }

    // 更新排程
    public static function updateSchedule($id, $machineName, $scheduleDate)
    {
        return Schedule::where('id', $id)->update(['machine_name' => $machineName, 'schedule_date' => $scheduleDate]);
    }

    // 取得達成率
    public static function getAchievementRate($date, $machineName)
    {
        $result = Schedule::select(
            DB::raw('SUM(orders.plan_qty) as plan_qty'),
            DB::raw('SUM(orders.actual_qty) as actual_qty')
        )
        ->join('orders', 'schedules.order_id', '=', 'orders.id')
        ->where('schedules.schedule_date', $date)
        ->where('schedules.machine_name', $machineName)
        ->first();

        if (!$result || $result->plan_qty == 0) {
            return 0;
        }

        return round(($result->actual_qty / $result->plan_qty) * 100, 2);
    }

    // 取得執行效率
    public static function getExecutionEfficiency($date, $machineName)
    {
        // $result = Schedule::select(
        //     DB::raw('SUM(erp_work_hours) as basic_work_hours'),
        //     DB::raw('SUM(actual_time) as actual_time')
        // )
        // ->join('orders', 'schedules.order_id', '=', 'orders.id')
        // ->where('schedules.schedule_date', $date)
        // ->where('schedules.machine_name', $machineName)
        // ->first();
    }
}
