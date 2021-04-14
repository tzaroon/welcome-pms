<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDailyPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_prices', function (Blueprint $table) {
            $table->foreign('company_id', 'fk_daily_price_company')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('product_id', 'fk_daily_rates_products1')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('rate_type_id', 'fk_daily_rates_rate_types1')->references('id')->on('rate_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_prices', function (Blueprint $table) {
            $table->dropForeign('fk_daily_price_company');
            $table->dropForeign('fk_daily_rates_products1');
            $table->dropForeign('fk_daily_rates_rate_types1');
        });
    }
}
