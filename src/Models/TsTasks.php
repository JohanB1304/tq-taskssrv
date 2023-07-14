<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tasks\Tasks\Traits\FileTrait;

class TsTasks extends Model
{
    use HasUuids, FileTrait;

    public $incrementing = false;

    protected $table = 'ts_tasks';

    protected $primaryKey = 'guid';

    protected $uuidFieldName = 'guid';

    protected bool $primaryKeyIsUuid = true;

    protected $fillable = [
        'ref_id',
        'ref_entity',
        'title',
        'description',
        'followup_notes',
        'followup_file',
        'verification_notes',
        'verification_file',
        'date_start',
        'date_due',
        'inactivation_notes',
        'order',
        'reopened',
        'disabled',
        'status',
        'created_by',
        'updated_by',
        'closed_by',
        'expired_by',
        'closed_at',
        'expired_at',
        'tenant_id',
    ];

    protected $casts = [
        'date_start' => 'date:Y-m-d',
        'date_due' => 'date:Y-m-d',
        'order' =>  'integer',
        'reopened' => 'boolean',
        'disabled' => 'boolean',
        'expired_at' => 'timestamp',
        'closed_at' => 'timestamp',
    ];

    public function tags()
    {
        return $this->belongsToMany(TsTag::class,'ts_tasks_has_tags', 'task_id',
            'tag_id')
            // ->using(TaskHasObjects::class)
            ->withPivot(['status','tenant_id'])
            ->withTimestamps();
    }

    public function index($refId, $request = null){
        $tasks = TsTasks::where('ref_id', $refId);

        $tasks = $tasks->leftJoin('ts_tasks_has_tags as pivot',function($join){
                $join->on('ts_tasks.guid', '=', 'pivot.task_id');
            })
            ->leftJoin('ts_tags as tag', function($join){
                $join->on('pivot.tag_id', '=', 'tag.guid');
            })
            ->select('ts_tasks.guid as id', 'title', 'description', 'followup_notes', 'followup_file',
                'verification_notes', 'verification_file', 'date_start', 'date_due', 'ts_tasks.created_by as responsable',
                'pivot.tag_id', 'tag.label as tag_label', 'tag.type as type_tag', 'reopened', 'disabled',
                'ts_tasks.status')
            ->orderBy(DB::raw('ISNULL(`order`), `order`'),'ASC')->get();

        $tasks->each(function($task){
            $task['verified'] = $task->status === 'VERIFIED'?true:false;
            if($task->status =='CLOSED' || $task->status == 'VERIFIED'){
                $task['status'] = true;
            } else if($task->status=='ACTIVE'){
                $task['status'] = false;
            }
        });
        

        return $tasks;
    }

    public function store($request = null, $refId, $tag=null, $modelTask = null){
        $lastTask = self::where('ref_id', $refId)
            ->orderByDesc('order')->first();
        $refId1 = Str::before($refId, '|');
        $refId2 = Str::after($refId, '|');
        $refEntity1 = Str::before(config('task.ref_entity'), '::class|');
        $ref1 = $refEntity1::whereUuid($refId1)->first(['tenant_id']);
        $task = self::create([
            'title' => is_null($request)?$modelTask->title:$request->title,
            'date_start' => Carbon::now(),
            'date_due' => null,
            'ref_id' => $refId,
            'order' => is_null($lastTask)?0:$lastTask->order + 1,
            'created_by' => $request->status == false ? null : Auth::user()->full_name,
            'status' => 'ACTIVE',
            'tenant_id' => $ref1->tenant_id
        ]);
        if(!is_null($tag)){
            $this->attachTags($task, $tag);
        }
        return $task;
    }

    public function updateTask($request, $refId, $taskId){
        $task = self::where('guid', $taskId)->firstOrFail();

        $title = $task->title;
        $fileName = $task->followup_file;
        $fileVerifyName = $task->verification_file;
        $verifyDescription = $task->verification_notes;
        $planDescription = $task->followup_notes;
        $description = $task->description;
        $dateDue = $task->date_due;
        $responsable = $task->created_by;
        $status = $task->status;

        if ($request->has('followup_file') && !is_null($request->followup_file)) {
            if(!is_null($fileName)){
                if(Storage::disk('local')->exists($fileName)){
                    Storage::disk('local')->delete($fileName);
                }
            }
            $fileName = $this->storeFile($request->followup_file);
        }
        if ($request->has('verification_file') && !is_null($request->verification_file)) {
            if(!is_null($fileVerifyName)){
                if(Storage::disk('local')->exists($fileVerifyName)){
                    Storage::disk('local')->delete($fileVerifyName);
                }
            }
            $fileVerifyName = $this->storeFile($request->verification_file);
        }

        if ($request->has('status')) {
            if($task->status == 'VERIFIED'){
                $status = 'VERIFIED';
            }else{
                $status = $request->status == false ? 'ACTIVE' : 'CLOSED';
            }

        }

        if ($request->has('description')) {
            $description = $request->description;
        }
        if ($request->has('title')) {
            $title = $request->title;
        }
        if ($request->has('verification_notes')) {
            $verifyDescription = $request->verification_notes;
        }
        if ($request->has('followup_notes')) {
            $planDescription = $request->followup_notes;
        }
        if ($request->has('date_due')) {
            $dateDue = $request->date_due;
        }
        if ($request->has('responsable')) {
            if(!is_null($request->responsable)){
                // Eliminar caracteres no válidos
                $hexData = preg_replace('/[^[:xdigit:]]/', '', $request->responsable);
                // Convertir a formato binario
                $binData = hex2bin($hexData);
                $responsable = User::where('id', $binData)->first()->full_name;
            }

        }
        if ($request->has('followup_file') && is_null($request->followup_file) && $request->followup_delete) {
            if(!is_null($fileName)){
                if(Storage::disk('local')->exists($fileName)){
                    Storage::disk('local')->delete($fileName);
                }
            }
            $fileName = null;
        }
        if ($request->has('verification_file') &&is_null($request->verification_file) && $request->verification_delete) {
            if(!is_null($fileVerifyName)){
                if(Storage::disk('local')->exists($fileVerifyName)){
                    Storage::disk('local')->delete($fileVerifyName);
                }
            }
            $fileVerifyName = null;
        }

        $task->update([
            'title' => $title,
            'status' => $status,
            'description' => $description,
            'verification_notes' => $verifyDescription,
            'verification_file' => $fileVerifyName,
            'followup_notes' =>$planDescription,
            'followup_file' =>$fileName,
            'date_due' => $dateDue,
            'created_by' => $responsable,
            'closed_by' => $request->status == false ? null : (is_null(Auth::user())?null:Auth::user()->full_name),
            'closed_at' => $request->status == false ? null : Carbon::now(),
            'updated_by' => is_null(Auth::user())?null:Auth::user()->full_name
        ]);
        if($request->has('tag')){
            $this->attachTags($task,$request->tag);
        }

        return $task;
    }

    private function storeFile($file){
        $file_extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $data= substr($file, strpos($file, ',')+1);
        $data=base64_decode($data);
        $extension=$this->validateExtension($file_extension);
        $fileName=time().'_'.'file'.'.'.$extension;
        Storage::disk('local')->put($fileName, $data);
        return $fileName;
    }

    public function storeOrder($ids){
        $count=1;
        foreach($ids as $id){
            $task = self::where('guid', $id)->first();
            if($task->status != 'DISABLED'){
                $task->update(['order' => $count]);
                $count+=1;
            }
        }
        return true;
    }

    public function verify($taskId, $status){
        
        $task= self::leftJoin('ts_tasks_has_tags as pivot',function($join){
                $join->on('ts_tasks.guid', '=', 'pivot.task_id');
            })
            ->leftJoin('ts_tags as tag', function($join){
                $join->on('pivot.tag_id', '=', 'tag.guid');
            })
            ->where('ts_tasks.guid', $taskId)
            ->select('ts_tasks.guid as id', 'title', 'description', 'followup_notes', 'followup_file',
                'verification_notes', 'verification_file', 'date_start', 'date_due', 'ts_tasks.created_by as responsable',
                'pivot.tag_id', 'tag.label as tag_label', 'tag.type as type_tag', 'reopened', 'disabled',
                'ts_tasks.status')->first();
        if ($status) {
            $task->update(['status'=>'VERIFIED']);
        } elseif($task->status == 'VERIFIED'){
            $task->update([
                'status'=>'CLOSED',
                'reopened' => true
            ]);
        }
        $task['verified'] = $task->status === 'VERIFIED'?true:false;
        if($task->status =='CLOSED' || $task->status == 'VERIFIED'){
            $task['status'] = true;
        } else if($task->status=='ACTIVE'){
            $task['status'] = false;
        }
        return $task;
    }

    public function disable($refId, $taskId, $justification){
        $task = self::where('guid', $taskId)->firstOrFail();
        $task->update([
            'disabled'=> true,
            "inactivation_notes"=>$justification,
            'order' => null,
            'updated_by' => is_null(Auth::user())?null:Auth::user()->full_name
        ]);
        return self::where('ref_id', $refId)->orderBy(DB::raw('ISNULL(`order`), `order`'),'ASC')
            ->get();
    }

    public function getTotal($refId){
        $refId = Str::before($refId, '|');
        return self::where('ref_id','LIKE', "$refId|%")->where('status','ACTIVE')->count();
    }

    public function attachTags($task,$tag)
    {
        TsTasksHasTags::where('task_id', $task->guid)->delete();
        if(!is_null($tag)){
            $tag = $tag;
            if(!is_null(TsTag::where('guid', $tag)->first())){
                $task->tags()->attach($tag,[
                    'status' => 'active',
                    'tenant_id' => $task->tenant_id
                ]);
            }
        }
    }

    public function getFile($taskId, $type){
        $task = self::where('guid', $taskId)->firstOrFail();
        $dataType=$this->validateFileType(substr($task->{$type},strpos($task->{$type}, '.')+1));
        $file=$this->getBase64File($task->{$type});
        return [
            'file_name' => $task->{$type},
            'file' => $file,
            'type' => $dataType,
            'status_code' => '200',
            'message' => 'ok'
        ];
    }

    public function getTags($refId){
        return  self::where('ref_id', $refId)
            ->join('ts_tasks_has_tags as pivot',function($join){
                $join->on('ts_tasks.guid','=','pivot.task_id');
            })
            ->join('ts_tags as tags',function($join){
                $join->on('pivot.tag_id','=','tags.guid');
            })
            ->select(
                'tags.guid as tag_id',
                'tags.label as tag_label',
                'tags.type as type_tag',
            )->get();
        // $queryObject = SpecifiesTasks::whereUuid($modelRequest,'request_id')
        //     ->whereUuid($stage,'stage_id')
        //     ->join('wk_task_has_objects as pivot_object',function($join){
        //         $join->on('wk_specifies_task.guid','=','pivot_object.task_id');
        //     })
        //     ->join('wk_assistant_daruma_classifiers as object',function($join){
        //         $join->on('pivot_object.object_id','=','object.guid');
        //     })
        //     ->join('wk_type_object as type_object_1',function($join){
        //         $join->on('object.type_object_id','=','type_object_1.guid');
        //     })
        //     ->select(
        //         'object.guid as id',
        //         'object.reference_name as name',
        //         'type_object_1.default_name as type_object_name',
        //     );

    }

}