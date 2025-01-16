<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignCoupon extends Model
{
    use HasFactory;
    public $table = "assign_customer_coupons";
    protected $fillable = ['user_id', 'assign_type ', 'customer_id', 'event_id', 'coupon_number'];
}
