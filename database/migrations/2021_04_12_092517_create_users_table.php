<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index('fk_users_companies1_idx');
            $table->string('title', 45)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'none'])->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number', 45)->nullable();
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('api_token')->nullable();
            $table->string('street', 45)->nullable();
            $table->string('building_no', 45)->nullable();
            $table->integer('floor')->nullable();
            $table->string('postal_code', 45)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('country_id', 45)->nullable();
            $table->string('language_id', 45)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('photo')->nullable();
            $table->string('otp', 45)->nullable();
            $table->timestamp('otp_generated_at')->nullable();
            $table->boolean('can_login')->default(0);
            $table->timestamp('last_signin')->nullable();
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
        Schema::dropIfExists('users');
    }
}
