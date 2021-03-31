<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_bookings_company_idx');
            $table->unsignedInteger('booker_id')->index('fk_bookings_bookers1_idx');
            $table->date('reservation_from');
            $table->date('reservation_to');
            $table->string('time_start', 5)->nullable();
            $table->enum('status', ['confirmed', 'check-in', 'check-out']);
            $table->enum('payment_status', ['not-paid', 'partially-paid', 'payed']);
            $table->float('total_price', 10, 0)->nullable();
            $table->enum('source', ['business', 'google', 'other', 'direct'])->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
