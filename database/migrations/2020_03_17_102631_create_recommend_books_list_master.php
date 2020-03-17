<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendBooksListMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_recommend_books_list_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('등록 사용자 아이디.');
            $table->unsignedBigInteger('book_id')->nullable()->comment('books_master id 값.');
            $table->enum('active', ['Y', 'N'])->default('Y')->comment('사용 유무.');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('book_id')->references('id')->on('tbl_books_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_recommend_books_list_master');
    }
}
