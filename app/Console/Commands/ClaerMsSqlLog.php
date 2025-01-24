<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClaerMsSqlLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:claer-ms-sql-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除MSSQL的Log';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = DB::connection('sqlsrv');
        $connection->statement("ALTER DATABASE [Schedule] SET RECOVERY SIMPLE WITH NO_WAIT");
        $connection->statement("DBCC SHRINKFILE(Schedule_log, 1)");
        $connection->statement("ALTER DATABASE [Schedule] SET RECOVERY FULL WITH NO_WAIT");

        $this->info('Database maintenance completed successfully');
        return Command::SUCCESS;
    }
}
