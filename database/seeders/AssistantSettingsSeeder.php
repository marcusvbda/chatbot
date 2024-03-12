<?php

namespace Database\Seeders;

use App\Models\AssistantSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssistantSettingsSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        $this->createSettings();
        DB::commit();
    }

    protected function createSettings()
    {
        AssistantSetting::create(["key" => "openia_base_url", "value" => "https://api.openai.com"]);
        AssistantSetting::create(["key" => "openia_api_key", "value" => env("OPENIA_API_KEY")]);
    }
}
