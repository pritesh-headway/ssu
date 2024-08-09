<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    public $table = "events";
    protected $fillable = ['event_name', 'image', 'event_description', 'prize', 'image_type','start_date','end_date'];
}
