<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hotel_id', 45);
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('product_id')->index('fk_accessories_products1_idx');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extras');
    }
}
