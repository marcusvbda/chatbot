<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        $this->createSettings();
        DB::commit();
    }

    protected function createSettings()
    {
        Setting::create(["key" => "openia_base_url", "value" => "https://api.openai.com"]);
        Setting::create(["key" => "openia_api_key", "value" => env("OPENIA_API_KEY")]);
    }
}