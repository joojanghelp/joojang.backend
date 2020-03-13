<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('type')->references('code_id')->on('tbl_codes_master')->onDelete('cascade');
	        $table->foreign('state')->references('code_id')->on('tbl_codes_master')->onDelete('cascade');
            $table->foreign('level')->references('code_id')->on('tbl_codes_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
		    $table->dropForeign('users_level_foreign');
		    $table->dropForeign('users_state_foreign');
		    $table->dropForeign('users_type_foreign');
	    });

    }
}
