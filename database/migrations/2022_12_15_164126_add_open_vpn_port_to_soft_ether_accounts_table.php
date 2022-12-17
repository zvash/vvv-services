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
        Schema::table('soft_ether_accounts', function (Blueprint $table) {
            $table->unsignedInteger('open_vpn_port')->nullable()->after('assigned_to')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soft_ether_accounts', function (Blueprint $table) {
            $table->dropColumn('open_vpn_port');
        });
    }
};
