<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apartman')->length(1)->unsigned();
            $table->date('dolazak');
            $table->date('odlazak');
            $table->string('rez_preko', 50);
            $table->decimal('cijena', 7, 2);
            $table->decimal('provizija', 6, 2)->nullable();
            $table->decimal('pdv', 6, 2)->nullable();
            $table->integer('no_guests')->length(1)->unsigned();
            $table->text('napomena')->nullable();
            $table->string('color', 20);
            $table->string('status', 20);
            $table->integer('guest_id')->length(10)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
