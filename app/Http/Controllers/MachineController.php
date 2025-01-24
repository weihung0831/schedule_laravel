<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MachineService;

class MachineController extends Controller
{
    /**
     * 取得機台資料
     */
    public function get(Request $request)
    {
        $validated = $request->validate([
            'product_line_code' => 'required|string',
        ]);

        try {
            $where = [
                'product_line_code' => $validated['product_line_code'],
            ];

            return MachineService::getMachines($where);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 取得機台時數
     */
    public function getMachineWorkHours(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|string',
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        try {
            $result = MachineService::getMachineWorkHours($validated['machine_name'], $validated['begin_date'], $validated['end_date']);

            $result = $result->groupBy('schedule_date')
                ->map(function ($items) {
                    return [
                        'date' => str_replace('-', '', $items->first()->schedule_date),
                        'machine_name' => $items->first()->machine_name,
                        'changed_manual_work_hours' => $items->sum('changed_manual_work_hours'),
                    ];
                })->values();

            return response()->json([
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
