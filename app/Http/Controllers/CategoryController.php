<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $create = DB::table("categories")->insert([
            "nameCategory"=>$request["nameCategory"],
        ]);
        return $create;
    }

    public function update(Request $request){
        $products = DB::table("categories")->where("id", $request
            ->get("id"))
            ->update([
                "nameCategory"=>$request["nameCategory"]
            ]);
            
        return $products;
    }

    public function delete(Request $request){
        $request = DB::table("categories")->where("id", $request->get("id"))->delete();
        return $request;
    }

    public function get(){
        $products = DB::table("categories")->get();
        return $products;
    }
}

