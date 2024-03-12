<?php

use Database\Seeders\SettingsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use marcusvbda\supernova\seeders\PermissionSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("settings", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });
        $aclSeeder = new PermissionSeeder();
        $aclSeeder->makePermissions('Configurações', 'settings');
        (new SettingsSeeder())->run();
    }

    public function down(): void
    {
        Schema::dropIfExists("settings");
        $aclSeeder = new PermissionSeeder();
        $aclSeeder->deletePermissionType('Assistentes');
    }
};