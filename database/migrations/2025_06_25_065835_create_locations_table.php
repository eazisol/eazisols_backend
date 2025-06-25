<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(); // Optional label like Home, Office
            $table->string('address_line_1');   // Required street address
            $table->string('address_line_2')->nullable(); // Optional apartment/suite
            $table->string('area')->nullable();           // Optional neighborhood
            $table->string('city');              // Required city
            $table->string('state');             // Required state/province
            $table->string('zip_code')->nullable(); // Optional postal/zip code
            $table->string('country');           // Required country
            $table->timestamps();                // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
