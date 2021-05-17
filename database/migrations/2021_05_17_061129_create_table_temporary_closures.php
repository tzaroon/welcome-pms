<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTemporaryClosures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_temporary_closures', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('room_id', false, true);
            $table->date('from_date');
            $table->date('to_date');
            $table->text('reason');
            
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
        Schema::dropIfExists('table_temporary_closures');
    }
}
