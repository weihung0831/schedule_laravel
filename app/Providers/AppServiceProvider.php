<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ERP\Interfaces\MachineSync;
use App\Services\ERP\Interfaces\ProductionLineSync;
use App\Services\ERP\Interfaces\OrderSync;
use App\Services\Erp\MachineProvider;
use App\Services\Erp\ProductLineProvider;
use App\Services\Erp\OrderProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 註冊應用程式服務
     */
    public function register(): void
    {
        $this->app->bind(ProductionLineSync::class, ProductLineProvider::class);
        $this->app->bind(MachineSync::class, MachineProvider::class);
        $this->app->bind(OrderSync::class, OrderProvider::class);
    }
}
