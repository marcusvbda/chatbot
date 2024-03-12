<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainRow extends Model
{
    protected $table = "train_rows";
    public $guarded = ["created_at"];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, "assistant_id");
    }
}
