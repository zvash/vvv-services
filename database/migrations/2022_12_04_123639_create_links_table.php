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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->string('server');
            $table->boolean('has_tls')->default(false);
            $table->boolean('tunneled')->default(false);
            $table->unsignedBigInteger('limit')->default(0);
            $table->unsignedInteger('consumer_count')->default(0);
            $table->string('consumer_country')->nullable();
            $table->unsignedBigInteger('visit_count')->default(0);
            $table->boolean('still_valid')->default(true);
            $table->date('last_visit')->nullable();
            $table->string('setting_ps')->nullable();
            $table->string('setting_id')->nullable();
            $table->string('setting_port')->nullable();
            $table->string('setting_add')->nullable();
            $table->string('setting_tls')->nullable();
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
        Schema::dropIfExists('links');
    }
};
