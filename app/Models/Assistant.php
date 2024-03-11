<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    protected $table = "assistants";
    public $guarded = ["created_at"];
}
