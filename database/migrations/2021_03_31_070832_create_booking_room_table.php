<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_room', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id')->index('fk_bookings_has_rooms_bookings1_idx');
            $table->unsignedInteger('room_id')->index('fk_bookings_has_rooms_rooms1_idx');
            $table->integer('rate_type_id');
            $table->string('first_guest_name', 50)->nullable();
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
        Schema::dropIfExists('booking_room');
    }
}
