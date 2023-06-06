<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Products;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class ProductController extends Controller
{
    public function get(Request $request){
        $page = $request->get('page') ? $request->get('page') : 1;
        $search = $request->get('search')=="null" ? $request->get('search') : "";
        $limit = 10;
        $products = Products::with("image")
            ->where("name", "like", "%" . $request->get("search") . "%")
            ->where('categories', "like", "%" . $request->get('category') . "%")        
            ->skip(($page-1)*10)
            ->take(($page)*10)
            // ->orderBy($request->get('column'), $request->get('order'))
            ->get();
        $count = Products::where("name", "like", "%" . $request->get("search") . "%")
            ->where('categories', "like", "%" . $request->get('category') . "%")
            ->count();
        return response()->json(['products'=>$products, 'count'=>$count]);
    }

    public function create(Request  $request){
        // $validator = Validator::make($request->all(), [
        //     'image' => [
        //         'required|mimes:jpg,jpeg,png,bmp|max:20000'
        //     ]
        // ]);
        // $files = [$request['image']];
        
        // if(!count($files)){
        //     return false;
        // }

        $create = DB::table("products")->insertGetId([
            "name"=>$request["name"],
            "description"=>$request["description"],
            "imgUrl"=>"http://localhost:8000/api/file?file",
            "price"=>$request["price"],
            "brand"=>$request["brand"],
            "count"=>$request["count"],
            "code"=>$request["code"],
            "size"=>$request["size"],
            "categories"=>$request["categories"]
        ]);

        $files = $request->file('image');
        foreach($files as $file){
            $extension = $file->extension();
            $fileName = Str::uuid();
            $file->move(storage_path(),"$fileName.$extension");
            DB::table("images")->insert([
                "imgUrl"=>"http://80.78.247.66/api/file?file=$fileName.$extension",
                "products_id"=>$create
            ]);
        }

        return $files;
    }
    
    public function getFile(Request $request)
    {
        $filePath = storage_path('hostingproject'.$request->input("file"));
        return $request;
        // return response()->file($filePath, ['Content-Type: multipart/form-data']);
    }

    public function getOne(Request $request){
        $products2 = Products::with("image")->where("id", $request["id"])->first();
        return response()->json($products2);
    }

    public function update(Request $request){
        $products = DB::table("products")->where("id", $request
            ->get("id"))
            ->update([
                "name"=>$request["name"],
                "description"=>$request["description"],
                "imgUrl"=>$request["imgUrl"],
                "price"=>$request["price"],
                "brand"=>$request["brand"],
                "count"=>$request["count"],
                "code"=>$request["code"],
                "size"=>$request["size"]
            ]);
        
        return $products;
    }

    public function delete(Request $request){
        $request2 = Image::where("products_id", $request->get("id"))->delete();
        $request = DB::table("products")->where("id", $request->get("id"))->delete();
        return $request;
    }
}
