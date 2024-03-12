<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use marcusvbda\supernova\seeders\PermissionSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("assistants", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('openia_id')->nullable();
            $table->string('name');
            $table->longText('instructions');
            $table->timestamps();
        });

        Schema::create("train_rows", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->longText('user');
            $table->longText('assistant');
            $table->unsignedBigInteger('assistant_id');
            $table->foreign('assistant_id')->references('id')->on('assistants')->onDelete('cascade');
            $table->timestamps();
        });
        $aclSeeder = new PermissionSeeder();
        $aclSeeder->makePermissions('Assistentes', 'assistants');
    }

    public function down(): void
    {
        Schema::dropIfExists("assistants");
        $aclSeeder = new PermissionSeeder();
        $aclSeeder->deletePermissionType('Assistentes');
    }
};
