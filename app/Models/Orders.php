<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
class Orders extends Model
{
    use HasFactory;
    protected $fillable = ['users_id', "E-Mail", "name", "status", "number", "addres", "comment"];

    public function allOrders()
    {
        $allorders = new AllOrders;
        
        return $this->hasMany(AllOrders::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "users_id");
    }

    public function products() 
    {
        $allorders = new AllOrders;
        return $allorders->products();
    }
}
