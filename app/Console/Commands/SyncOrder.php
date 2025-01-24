<?php

namespace App\Console\Commands;

use App\Http\Controllers\DBAController;
use App\Services\ERP\Interfaces\MachineSync;
use App\Services\ERP\Interfaces\OrderSync;
use App\Services\ERP\Interfaces\ProductionLineSync;
use Illuminate\Console\Command;

class SyncOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步工單資料';

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
        $controller->syncOrderData();

        $this->info('工單資料同步成功');
        return Command::SUCCESS;
    }
}
