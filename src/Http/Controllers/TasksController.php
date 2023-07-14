<?php
namespace TqTaskssrv\TqTaskssrv\Http\Controllers;

use App\Models\TsTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController
{
    public function index($prefix=null, $refId){
        $task = new TsTasks();
        $tasks = $task->index($refId);
        return response()->json([
            'data' => $tasks,
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function store(Request $request, $prefix, $refId){
        $task = new TsTasks();
        $task->store($request, $refId);
        
        return response()->json([
            'data' => $task->index($refId),
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function update(Request $request, $prefix=null, $refId,  $taskId){
        $task = new TsTasks();
        $task->updateTask($request, $refId, $taskId);
        return response()->json([
            'data' => $task->index($refId, $request),
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function saveOrder(Request $request, $prefix){
        $task = new TsTasks();
        $tasks = json_decode($request->getContent());
        $task->storeOrder($tasks->tasks);
        
        return response()->json([
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function verify(Request $request, $prefix, $refId, $taskId){
        $task = new TsTasks();        
        $task = $task->verify($taskId, $request->status);
        return response()->json([
            'data' => $task,
            'status_code'=> '200',
            'message' => 'ok'
        ]);
    }

    public function disable(Request $request, $prefix, $refId, $taskId){
        $task = new TsTasks();
        $task->disable($refId, $taskId, $request->justification);
        return response()->json([
            'data' => $task->index($refId),
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function getTotalCompleteTasks($prefix, $refId){
        $tasks = new TsTasks();
        return response()->json([
            'pending_tasks' => $tasks->getTotal($refId),
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function getFile(Request $request, $prefix=null, $taskId){
        $type = $request->type;
        if($type == 'verification'){
            $type = 'verification_file';
        }else if($type == 'followup'){
            $type = 'followup_file';
        }

        $task = new TsTasks();
        $file = $task->getFile($taskId, $type);
        return response()->json(array_merge($file,[
            'status_code' => '200',
            'message' => 'ok'
        ]));
       
    }

    public function getTags($prefix=null, $refId){
        $tasks = new TsTasks();
        $tags = $tasks->getTags($refId);
        return response()->json([
            'data' => $tags,
            'status_code' => '200',
            'message' =>  'ok'
        ]);

    }
}