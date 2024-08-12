<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatmessage extends Model
{
    use HasFactory;
    public $table = "chatmessages";
    protected $fillable = ['sender_id', 'receiver_id', 'message'];
}
