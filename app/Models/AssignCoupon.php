<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignCoupon extends Model
{
    use HasFactory;
    public $table = "assign_customer_coupons";
    // protected $fillable = ['seller_id', 'coupon_format', 'customer_id', 'user_id', 'city'];
}
