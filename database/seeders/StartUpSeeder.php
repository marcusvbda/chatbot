<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        $this->createUsers();
        DB::commit();
    }

    protected function createUsers()
    {
        $now = now();
        User::insert([
            "name" => "Root",
            "email" => "root",
            "role" =>  "root",
            "dark_mode" => false,
            "password" => bcrypt("roottoor"),
            "created_at" => $now,
            "updated_at" => $now
        ]);
    }
}
