<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function get(){
        return News::all();
    }

    public function create(Request $request){
        $create = News::create([
            "title" => $request["title"],
            "body" => $request["body"]
        ]);
        return $this->get();
    }

    public function delete(Request $request){
        $delete = News::where('id', $request->get('id'))->delete();
        return $this->get();
    }

    public function update(Request $request){
        $update = News::where('id', $request->get('id'))->update([
            "title" => $request["title"],
            "body" => $request["body"]
        ]);
        return $this->get();
    }
}
