<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faToAdminTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->boolean('two_fa')->default(0)->after('status')->comment('0: two-FA off, 1: two-FA on');
            $table->boolean('two_fa_verify')->default(1)->after('status')->comment('0: two-FA unverified, 1: two-FA verified');
            $table->string('two_fa_code',50)->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
}
