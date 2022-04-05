<?php

namespace App\Models;

use App\Models\Sync;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    public $incrementing = true;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($model) {
            Sync::create([
                'table_name' => $model->table,
                'connection' => $model->connection,
                'table_id' => $model->id,
                'query_type' => 'insert'
            ]);
        });

        static::deleting(function ($model) {
            $pivot = self::where($model->attributes)->first();
            Sync::create([
                'table_name' => $model->table,
                'connection' => $model->connection,
                'table_id' => $pivot->id,
                'query_type' => 'delete'
            ]);
        });
    }
}
