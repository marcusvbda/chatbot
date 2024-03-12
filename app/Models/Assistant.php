<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    protected $table = "assistants";
    public $guarded = ["created_at"];

    public function trainRows()
    {
        return $this->hasMany(TrainRow::class, "assistant_id");
    }

    public function assistantSettings()
    {
        return $this->hasMany(AssistantSetting::class, "assistant_id");
    }
}
