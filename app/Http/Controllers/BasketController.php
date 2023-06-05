<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Basket;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    //

    public function create(Request $request)
    {
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $create = Basket::create([
            "products_id" => $request["id"],
            "users_id" => $apy["id"],
            "count" => $request["count"],
        ]);
        $get = $this->getUserBasket($request);
        return $get;
    }

    public function delete(Request $request)
    {
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $delete = Basket::where("id", $request["id"])->where("users_id", $apy["id"])->delete();
        $get = $this->getUserBasket($request);
        return $get;
    }

    public function updateCount(Request $request)
    {
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $delete = Basket::where("id", $request["id"])
            ->where("users_id", $apy["id"])
            ->update(["count" => $request["count"]]);
        $get = $this->getUserBasket($request);
        return $get;
    }

    public function getUserBasket(Request $request)
    {
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $get = Basket::where("users_id", $apy["id"])
            ->with([
                "products" => [
                    "image"
            ]])
            ->get();
        return $get;
    }
}
