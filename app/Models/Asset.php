<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    public $table = "asset_orders";
    protected $fillable = ['user_id','event_id','title','detail','quantity','amount', 'order_status'];
}
