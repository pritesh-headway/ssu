<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    use HasFactory;
    public $table = "winners";
    protected $fillable = ['user_id', 'customer_id', 'prize_id', 'event_id', 'coupon_number'];
}
