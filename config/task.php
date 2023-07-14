<?php

return [
    'base_url' => env('AN_BASE_URL','https://tqchangecontrolsrv.metadockit.com/'),
    'ref_entity' => env('TS_REF_ENTITY','\App\Models\Request::class|\App\Models\Stage::class|'),
    'component_name' => env('TS_COMPONENT_NAME','tasks'),
    'main_table_name' => env('TS_TABLE_NAME','ts_tasks')
];