<?php

Route::prefix('api/{slugTenant}/task/')->group(function(){
    Route::post('{task_id}/file', [Tasks\Tasks\Http\Controllers\TasksController::class, 'getFile']);
    Route::post('tags',[Tasks\Tasks\Http\Controllers\TagsController::class, 'store']);
    Route::post('store-order',[Tasks\Tasks\Http\Controllers\TasksController::class, 'saveOrder']);
    Route::get('{ref_id}',[Tasks\Tasks\Http\Controllers\TasksController::class, 'index']);/** */
    Route::post('{ref_id}',[Tasks\Tasks\Http\Controllers\TasksController::class, 'store']);/** */
    Route::put('{ref_id}/{task_id}',[Tasks\Tasks\Http\Controllers\TasksController::class, 'update']);/** */
    Route::post('{ref_id}/{task_id}/disable',[Tasks\Tasks\Http\Controllers\TasksController::class, 'disable']);
    Route::post('{ref_id}/{task_id}/verify',[Tasks\Tasks\Http\Controllers\TasksController::class, 'verify']);
    Route::get('{ref_id}/get_qty_complete_tasks',[Tasks\Tasks\Http\Controllers\TasksController::class, 'getTotalCompleteTasks']);
    Route::get('{ref_id}/tags',[Tasks\Tasks\Http\Controllers\TasksController::class, 'getTags']);
    Route::get('{ref_id}/tags/available',[Tasks\Tasks\Http\Controllers\TagsController::class, 'index']);
    

});