<?php

namespace App\Console\Commands;

use App\Http\Controllers\DBAController;
use App\Services\ERP\Interfaces\MachineSync;
use App\Services\ERP\Interfaces\OrderSync;
use App\Services\ERP\Interfaces\ProductionLineSync;
use Illuminate\Console\Command;

class SyncMachines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-machines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步機台資料';

    public function __construct(
        private ProductionLineSync $productionLineSync,
        private MachineSync $machineSync,
        private OrderSync $orderSync
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new DBAController($this->productionLineSync, $this->machineSync, $this->orderSync);
        $controller->syncMachineData();

        $this->info('機台資料同步成功');
        return Command::SUCCESS;
    }
}
