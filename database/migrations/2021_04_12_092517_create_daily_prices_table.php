<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_daily_price_company_idx');
            $table->unsignedInteger('rate_type_id')->index('fk_daily_rates_rate_types1_idx');
            $table->unsignedInteger('product_id')->index('fk_daily_rates_products1_idx');
            $table->date('date')->nullable();
            $table->tinyInteger('checkin_closed')->default(0);
            $table->tinyInteger('exit_closed')->default(0);
            $table->integer('minimum_stay')->nullable();
            $table->integer('maximum_stay')->nullable();
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
        Schema::dropIfExists('daily_prices');
    }
}
