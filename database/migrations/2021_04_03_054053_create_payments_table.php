<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id', false, true)->length(10);
            $table->enum('payment_method', ['bankcard', 'cash', 'giftcard', 'invoice', 'ota', 'creditcard']);
            $table->string('initials');
            $table->string('payment_date');
            $table->float('amount');
            $table->softDeletes();
            $table->timestamps();

            $table->index('booking_id');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
