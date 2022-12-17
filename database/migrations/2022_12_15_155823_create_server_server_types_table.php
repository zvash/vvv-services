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
        Schema::create('server_server_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id');
            $table->foreignId('server_type_id');
            $table->timestamps();

            $table->unique(['server_id', 'server_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server_server_types');
    }
};
