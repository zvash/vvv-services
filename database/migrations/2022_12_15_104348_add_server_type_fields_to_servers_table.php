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
        Schema::table('servers', function (Blueprint $table) {
            $table->foreignId('remote_server_id')->nullable()->default(null)->after('remote_server');
            $table->unsignedInteger('udp_port')->nullable()->default(null)->after('remote_server_id');
            $table->text('open_vpn_template')->nullable()->default(null)->after('udp_port');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('remote_server_id');
            $table->dropColumn('udp_port');
            $table->dropColumn('open_vpn_template');
        });
    }
};
