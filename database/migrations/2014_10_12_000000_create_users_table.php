<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('uuid', 50)->unique()->comment('사용자 uuid');
            $table->string('type', 6)->default('A02001')->comment('사용자 타입');
            $table->string('state', 6)->default('A10000')->comment('사용자 상태');
            $table->string('level', 6)->default('A20000')->comment('사용자 레벨');

            $table->enum('active', ['Y', 'N'])->default('Y')->comment('사용자 상태(정상인지 아닌지)');

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
        Schema::dropIfExists('users');
    }
}
