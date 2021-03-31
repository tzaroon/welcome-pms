<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingDateBookingsHasProductPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings_has_product_prices', function (Blueprint $table) {
            $table->enum('extras_pricing', ['by_day', 'by_person_per_day', 'by_person_per_stay', 'full_stay'])->nullable();
            $table->date('extras_date')->nullable();
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
