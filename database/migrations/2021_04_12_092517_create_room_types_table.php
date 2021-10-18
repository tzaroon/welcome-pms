<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_company_room_types1_idx');
            $table->unsignedInteger('hotel_id')->index('fk_hotel_room_type1_idx');
            $table->unsignedInteger('category_id')->index('fk_category_id_room_types1_idx');
            $table->unsignedInteger('room_types_id')->nullable()->index('fk_room_types_room_types1_idx');
            $table->integer('max_people')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('room_types');
    }
}
