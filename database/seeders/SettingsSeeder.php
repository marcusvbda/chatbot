<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $this->createSettings();
    }

    protected function createSettings()
    {
        Setting::truncate();
        Setting::create(["key" => "openia_base_url", "value" => "https://api.openai.com"]);
        Setting::create(["key" => "openia_api_key", "value" => env("OPENIA_API_KEY")]);
    }
}
