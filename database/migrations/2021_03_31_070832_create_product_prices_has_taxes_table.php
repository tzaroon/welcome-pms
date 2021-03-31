<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricesHasTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices_has_taxes', function (Blueprint $table) {
            $table->unsignedInteger('tax_id')->index('fk_products_has_taxes_taxes1_idx');
            $table->unsignedInteger('product_price_id')->index('fk_products_has_taxes_product_prices1_idx');
            $table->float('amount', 10, 0);
            $table->integer('percentage');
            $table->unsignedInteger('is_active')->default(1);
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
        Schema::dropIfExists('product_prices_has_taxes');
    }
}
