<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateTypeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_type_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_rate_type_details_company_idx');
            $table->unsignedInteger('rate_type_id')->index('fk_rate_type_names_rate_types1_idx');
            $table->string('language_id', 2);
            $table->string('name')->nullable();
            $table->string('unique_feature')->nullable();
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
        Schema::dropIfExists('rate_type_details');
    }
}
