<?php

use Database\Seeders\StartUpSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use marcusvbda\supernova\seeders\PermissionSeeder;

return new class extends Migration
{
    public function up(): void
    {
        (new StartUpSeeder())->run();
    }

    public function down(): void
    {
        //    
    }
};
