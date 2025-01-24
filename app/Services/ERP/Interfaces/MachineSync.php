<?php

namespace App\Services\ERP\Interfaces;

interface MachineSync
{
    /**
     * 取得機台資料
     */
    public function getMachines(): array;
}
