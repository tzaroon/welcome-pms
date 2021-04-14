<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_hotels_companies1_idx');
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->string('name');
            $table->string('property');
            $table->string('address');
            $table->string('zip', 45);
            $table->string('phone', 15);
            $table->string('email', 45);
            $table->string('vat_number', 45);
            $table->string('taxes', 45);
            $table->string('additional_taxes', 45)->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('max_booking_hour', 45)->nullable();
            $table->float('round_price', 10, 0)->nullable();
            $table->integer('cleaning_days')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_email')->nullable();
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
        Schema::dropIfExists('hotels');
    }
}
