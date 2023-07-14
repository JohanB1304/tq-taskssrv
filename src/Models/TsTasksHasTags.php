<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TsTasksHasTags extends Pivot
{
    // use GeneratesUuid;

    protected $table = 'ts_tasks_has_tags';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'task_id',
        'tag_id',
        'tenant_id',
        'status',
    ];

}