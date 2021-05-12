<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locks', function (Blueprint $table) {
            
            $table->increments('id');

            $table->integer('company_id');
            $table->string('lock_alias', 45);
            $table->string('lock_mac', 45);
            $table->unsignedInteger('lock_id');
            $table->text('lock_data');
            $table->integer('keyboard_pwd_version');
            $table->string('lock_name', 45);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locks');
    }
}
