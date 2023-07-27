<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class TsTag extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $table = 'ts_tags';

    protected $primaryKey = 'guid';

    protected $uuidFieldName = 'guid';

    protected bool $primaryKeyIsUuid = true;

    protected $fillable = [
        'label',
        'type',
        'status',
        'created_by',
        'updated_by',
        'expired_by',
        'expired_at',
        'tenant_id',
        'created_at',
        'updated_at'
    ];

    public function index($search){
        try{
            $count = 0;
            $tags = self::where('label', 'LIKE', "%$search%")
                ->orWhere('type', 'LIKE', "%$search%")
                ->get(['guid as id', 'label as name', 'type as type_tag']);
            
            $types = explode("|", config('task.tags_classes'));
            foreach($types as $class){
                $model = new $class();
                $type = $model->getTable();
                $query = $class::where('name', 'LIKE', "%$search%")
                    ->select('guid as id', 'name');
                $data[$count] = $query->addSelect(DB::raw("'$type' as type_tag"))->get();
                if($count>0){
                    $data[$count] = $data[$count]->mergeRecursive($data[$count-1])->all();
                }
                $count+=1;
            }
            $tags = $tags->mergeRecursive($data[$count-1]);
            return $tags->all();
        }catch(Throwable $e){
            return false;
        }
        
    }

    public function store($request){
        if($request->type){
            $type = $request->type;
        }else{
            $type = null;
        }
        $newTag = self::create([
            'label' => $request->label,
            'type' => $type,
            'created_by' => is_null(Auth::user())?null:Auth::user()->full_name,
            'tenant_id' => is_null(Auth::user())?null:Auth::user()->tenant_id
        ]);

        return $newTag;
    }
}