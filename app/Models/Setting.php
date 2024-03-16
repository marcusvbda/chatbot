<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "settings";
    public $guarded = ["created_at"];

    public static $KEYS = [
        ["value" => "openia_api_key", "label" => "Chave de API OpenIA"],
        ["value" => "openia_base_url", "label" => "Url da API OpenIA"],
        ["value" => "teste", "label" => "teste"],
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, "assistant_id");
    }
}
