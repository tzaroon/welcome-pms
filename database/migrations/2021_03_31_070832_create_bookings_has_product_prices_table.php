<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsHasProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_has_product_prices', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->index('fk_bookings_has_product_prices_bookings1_idx');
            $table->unsignedInteger('product_price_id')->index('fk_bookings_has_product_prices_product_prices1_idx');
            $table->integer('booking_has_room_id')->nullable();
            $table->unsignedInteger('extras_count')->nullable();
            $table->primary(['booking_id', 'product_price_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings_has_product_prices');
    }
}
