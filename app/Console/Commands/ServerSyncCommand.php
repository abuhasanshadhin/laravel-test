<?php

namespace App\Console\Commands;

use App\Jobs\SyncDataStoreJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ServerSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connection = @fsockopen('221.120.98.180', 80);

        if ($connection != false) {
            $syncUrl  = 'http://221.120.98.180/navy-cloth-server/api/get-sync-data';
            $unitId   = 2;
            $response = Http::get($syncUrl, ['unit_id' => $unitId]);

            if ($response->ok()) {
                $data = $response->collect();
                foreach ($data as $item) {
                    dispatch(new SyncDataStoreJob($item));
                }
            }
        }
    }
}
