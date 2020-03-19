<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBooksActivityMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_books_activity_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->nullable()->comment('책 id.');
            $table->unsignedBigInteger('user_id')->nullable()->comment('등록 사용자 아이디.');
            $table->string('uid', 50)->unique()->comment('글 고유값 uid');
            $table->string('gubun', 6)->nullable()->comment('구분 uid');
            $table->text('contents')->nullable()->comment('확동 내용.');
            $table->enum('active', ['Y', 'N'])->default('Y')->comment('사용 유무.');

            $table->timestamps();


            $table->foreign('book_id')->references('id')->on('tbl_books_master')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('gubun')->references('code_id')->on('tbl_codes_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_books_activity_master');
    }
}
