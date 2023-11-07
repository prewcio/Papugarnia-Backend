<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaneFaktursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dane_fakturs', function (Blueprint $table) {
            $table->id();
            $table->longText('firmName');
            $table->longText('street');
            $table->text('buildingNumber');
            $table->text('apartmentNumber')->nullable();
            $table->text('postalCode');
            $table->longText('city');
            $table->longText('NIPpesel')->nullable();
            $table->longText('email');
            $table->longText('phoneNumber');
            $table->integer('receiptNumber');
            $table->double('price');
            $table->date('date');
            $table->text('uzup');
            $table->boolean('wystawione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dane_fakturs');
    }
}
