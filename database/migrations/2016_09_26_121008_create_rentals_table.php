<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id');
            $table->integer('package_id');
            $table->string('addons');
            $table->string('duration');
            $table->string('name');
            $table->string('age');
            $table->string('sex');
            $table->string('ability');
            $table->string('weight');
            $table->string('height');
            $table->string('foot');
            $table->string('ski_length', 6)->nullable();
            $table->string('pole_length', 6)->nullable();
            $table->string('skier_code', 2)->nullable();
            $table->string('boot_size', 5)->nullable();
            $table->string('din', 6)->nullable();
            $table->longtext('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentals');
    }
};
