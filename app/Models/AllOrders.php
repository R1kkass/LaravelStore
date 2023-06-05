<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AllOrders extends Model
{
    use HasFactory;
    protected $fillable = ['orders_id', 'products_id', 'count'];

    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }

    public function products2()
    {
        return $this->belongsToMany(Products::class);
    }

    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
