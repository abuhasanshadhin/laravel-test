<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use App\Jobs\ResponseToServer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncDataStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    public $data;
    public $syncInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data     = $data['data'];
        $this->syncInfo = $data['sync_info'];
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resId = $this->syncInfo['table_id'];

        DB::beginTransaction();

        switch ($this->syncInfo['query_type']) {
            case 'insert':
                $resId = DB::table($this->syncInfo['table_name'])
                    ->insertGetId($this->data);
                break;

            case 'update':
                DB::table($this->syncInfo['table_name'])
                    ->where('id', $this->syncInfo['table_id'])
                    ->update($this->data);
                break;

            case 'delete':
                DB::table($this->syncInfo['table_name'])
                    ->where('id', $this->syncInfo['table_id'])
                    ->delete();
                break;

            default:
                break;
        }

        $syncUrl  = 'http://221.120.98.180/navy-cloth-server/api/send-sync-response-data';

        $res = Http::post($syncUrl, [
            'sync_id'   => $this->syncInfo['id'],
            'sync_type' => $this->syncInfo['sync_type'],
            'res_id'    => $resId,
            'unit_id'   => 2,
        ]);

        if ($res->ok()) {
            DB::commit();
            return 0;
        }

        DB::rollBack();
    }
}
