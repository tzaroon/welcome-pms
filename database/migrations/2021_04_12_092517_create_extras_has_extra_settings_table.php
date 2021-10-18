<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasHasExtraSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extras_has_extra_settings', function (Blueprint $table) {
            $table->unsignedInteger('extra_id')->index('fk_accessories_has_accessory_settings_accessories1_idx');
            $table->unsignedInteger('extra_setting_id')->index('fk_accessories_has_accessory_settings_accessory_settings1_idx');
            $table->string('value', 45)->nullable();
            $table->primary(['extra_id', 'extra_setting_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extras_has_extra_settings');
    }
}
