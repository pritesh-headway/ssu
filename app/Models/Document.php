<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    public $table = "documents";
    protected $fillable = ['doc_name', 'file', 'user_id', 'event_id'];
}
