<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNabywciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nabywcies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText('nazwa_firmy');
            $table->longText('adres_firmy');
            $table->text('kod_pocztowy');
            $table->longText('miasto');
            $table->longText('NIP')->nullable();
            $table->longText('email');
            $table->text('typ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nabywcies');
    }
}
