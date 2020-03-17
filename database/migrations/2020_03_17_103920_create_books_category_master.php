<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksCategoryMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_books_category_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->nullable()->comment('books_master id 값.');
            $table->string('gubun')->nullable()->comment('books 카테고리 코드 값.');
            $table->enum('active', ['Y', 'N'])->default('Y')->comment('사용 유무.');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('tbl_books_master')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_books_category_master');
    }
}
