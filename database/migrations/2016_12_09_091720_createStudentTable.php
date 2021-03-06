<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function ($table) {
            $table->increments('id')->unsigned();
            $table->text('firstname');
            $table->text('surname');
            $table->text('email');
            $table->text('nationality');
            $table->integer('address_id')->default(0);
            $table->integer('course_id')->default(0);
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
        //
    }
}
