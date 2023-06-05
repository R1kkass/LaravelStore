<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\AllOrders;
use App\Models\User;
use App\Models\Basket;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderController extends Controller
{
    public function create(Request $request) {
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $user = User::where("id", $apy["id"])->get();
        $create = Orders::create([
            "status" => "Выполняется",
            "E-Mail" => $apy["email"],
            "users_id" => $apy["id"],
            "number" => $request["number"],
            "addres" => $request["addres"],
            "name" => $request["name"],
            "comment" => $request["comment"]
        ]);
        $basket = Basket::where('users_id', $apy["id"])->get();
        if(count($basket)){
            foreach ($basket as $key => $unitOrder){
                $create2 = AllOrders::create([
                    "orders_id" => $create->id,
                    "products_id" => $unitOrder->products_id,
                    "count" => $unitOrder->count
                ]);
            }
            $basket = Basket::where('users_id', $apy["id"])->delete();
            return $basket;
        }
        return "Корзина пуста";
    }

    public function getOrderUser(Request $request) {
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $get = Orders::with("user")
            ->with(["allOrders" => [
                "products" => [
                    "image"
                ]
            ]])
            ->where("users_id", $apy["id"])
            ->orderBy("id", "desc")
            ->get();        
        return $get;
    }

    public function getAll(Request $request) {
        $getAll = Orders::with("user")
            ->with(["allOrders" => [
                "products" => [
                    "image"
                ]
            ]])->get();
        return $getAll;
    }

    public function editOrder(Request $request) {
        $put = Orders::where("id", $request['id'])->update(['status'=> $request['status']]);
        return $this->getAll($request);
    }
}
