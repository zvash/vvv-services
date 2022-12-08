<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('host')->nullable();
            $table->string('scheme')->default('http://');
            $table->string('ip');
            $table->unsignedInteger('panel_port')->nullable();
            $table->string('panel_username')->nullable();
            $table->string('panel_password')->nullable();
            $table->boolean('is_domestic')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
