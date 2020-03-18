<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_books_master', function (Blueprint $table) {

            $table->id();
            $table->string('uuid')->nullable()->comment('책 고유값.');
            $table->unsignedBigInteger('user_id')->nullable()->comment('등록 사용자 아이디.');
            $table->string('title')->nullable()->comment('title.');
            $table->string('authors')->nullable()->comment('authors.');
            $table->text('contents')->nullable()->comment('contents.');
            $table->string('isbn')->nullable()->comment('isbn.');
            $table->string('publisher')->nullable()->comment('publisher.');
            $table->string('thumbnail')->nullable()->comment('thumbnail.');
            $table->enum('active', ['Y', 'N'])->default('Y')->comment('사용 유무.');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_books_master');
    }
}
