<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;
    public $table = "prizes";
    protected $fillable = ['event_id', 'image', 'prize_name', 'prize_amount', 'prize_qty'];
}
