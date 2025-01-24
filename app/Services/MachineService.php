<?php

namespace App\Services;

use App\Models\Machine;
use App\Models\Schedule;

class MachineService
{
    /**
     * 批次建立或更新生產線資料
     */
    public static function batchCreateOrUpdate(array $machinesData): void
    {
        $machines = Machine::all();

        if ($machines->isEmpty()) {
            Machine::insert($machinesData);
        } else {
            Machine::truncate();
            Machine::insert($machinesData);
        }
    }

    /**
     * 取得機台資料
     */
    public static function getMachines(array $where)
    {
        $query = Machine::query();

        foreach ($where as $key => $value) {
            $query->where($key, $value);
        }

        $data = $query->get();

        return [
            'count' => count($data),
            'data' => $data,
        ];
    }

    /**
     * 取得機台時數
     */
    public static function getMachineWorkHours(string $machineName, string $beginDate, string $endDate)
    {
        return Schedule::where('machine_name', $machineName)->whereBetween('schedule_date', [$beginDate, $endDate])->get();
    }
}

