<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;
    protected $fillable = ['products_id', "users_id", "count"];
    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
