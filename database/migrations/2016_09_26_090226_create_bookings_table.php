<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chalet_id');
            $table->string('chalet_name')->nullable();
            $table->string('chalet_address')->nullable();
            $table->string('reference_number');
            $table->string('party_leader');
            $table->string('party_email');
            $table->string('party_mobile')->nullable();
            $table->string('arrival_datetime');
            $table->string('departure_datetime');
            $table->string('mountain_datetime');
            $table->integer('invoice_id')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
