<?php

namespace TqTaskssrv\TqTaskssrv\Http\Controllers;

use App\Models\TsTag;
use Illuminate\Http\Request;

class TagsController
{
    public function index(Request $request){
        $tags = new TsTag();
        return response()->json([
            'data' => $tags->index($request->search),
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