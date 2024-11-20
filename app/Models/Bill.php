<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    public $table = "bills";
    protected $fillable = ['user_id', 'event_id', 'title', 'amount', 'detail', 'file', 'receipt','bill_status'];
}
