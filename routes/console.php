<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// 同步生產線資料
Artisan::command('SyncProductLines', function () {
    $this->call('app:sync-productLines');
})->purpose('同步生產線資料')->hourly();

// 同步機台資料
Artisan::command('SyncMachines', function () {
    $this->call('app:sync-machines');
})->purpose('同步機台資料')->hourly();

// 同步工單資料
Artisan::command('SyncOrders', function () {
    $this->call('app:sync-orders');
})->purpose('同步工單資料')->daily();
