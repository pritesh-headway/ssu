<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    public $table = "banners";
    protected $fillable = ['banner_name', 'image', 'banner_type'];
}
