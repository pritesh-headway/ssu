<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    public $table = "event_details";
    protected $fillable = ['event_id', 'title', 'image', 'video', 'type'];
}
