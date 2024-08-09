<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slab extends Model
{
    use HasFactory;
    public $table = "slabs";
    protected $fillable = [
        'min_coupons',
        'max_coupons',
        'prize',
        'event_id',
    ];
}
