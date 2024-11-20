<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    public $table = "meeting";
    protected $fillable = ['meeting_title', 'link', 'is_today'];
}
