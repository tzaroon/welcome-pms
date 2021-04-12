<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsHasGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_has_guests', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->index('fk_bookings_has_guests_bookings1_idx');
            $table->unsignedInteger('guest_id')->index('fk_bookings_has_guests_guests1_idx');
            $table->primary(['booking_id', 'guest_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings_has_guests');
    }
}
