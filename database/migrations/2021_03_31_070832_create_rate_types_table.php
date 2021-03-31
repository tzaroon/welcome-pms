<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_rate_types_company_idx');
            $table->unsignedInteger('room_type_id')->index('fk_rate_types_room_types1_idx');
            $table->unsignedInteger('rate_type_id')->nullable()->index('fk_rate_types_rate_types1_idx');
            $table->unsignedInteger('number_of_people');
            $table->integer('advance');
            $table->boolean('show_in_booking_engine')->default(0);
            $table->float('price', 10, 0)->nullable();
            $table->float('amount_to_add', 10, 0)->nullable();
            $table->integer('percent_to_add')->nullable();
            $table->float('tax_1_amount', 10, 0)->nullable();
            $table->float('tax_2_amount', 10, 0)->nullable();
            $table->integer('tax_1_percentage')->nullable();
            $table->integer('tax_2_percentage')->nullable();
            $table->date('apply_rate_from')->nullable();
            $table->date('apply_rate_to')->nullable();
            $table->text('apply_rates_days')->nullable();
            $table->integer('checkin_closed')->nullable();
            $table->integer('exit_closed')->nullable();
            $table->integer('minimum_stay')->nullable();
            $table->integer('maximum_stay')->nullable();
            $table->string('max_booking_hour', 8)->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_types');
    }
}
