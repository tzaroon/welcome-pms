<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('user_id')->index('fk_bookers_users_idx');
            $table->string('company', 45)->nullable();
            $table->string('cif', 45)->nullable();
            $table->text('additional_information')->nullable();
            $table->boolean('sent_auto_emails')->default(1);
            $table->enum('identification', ['passport', 'id', 'others'])->nullable();
            $table->string('identification_number', 45)->nullable();
            $table->date('identification_date_of_expiry')->nullable();
            $table->integer('passport_country_id')->nullable();
            $table->float('discount_amount', 10, 0)->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->text('doc')->nullable();
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookers');
    }
}
