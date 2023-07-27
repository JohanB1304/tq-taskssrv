<?php

namespace TqTaskssrv\TqTaskssrv\Http\Controllers;

use App\Models\TsTag;
use Illuminate\Http\Request;

class TagsController
{
    public function index(Request $request){
        $tag = new TsTag();
        $tags = $tag->index($request->search);
        if($tags==false){
            return response()->json([
                'status_code' => '400',
                'message' => 'Modelo no encontrado'
            ]);
        }
        return response()->json([
            'data' => $tags,
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }

    public function store(Request $request){
        $tags = new TsTag();
        return response()->json([
            'data' => $tags->store($request),
            'status_code' => '200',
            'message' => 'ok'
        ]);
    }
}