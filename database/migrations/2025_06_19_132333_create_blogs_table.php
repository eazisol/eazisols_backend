<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('blogs', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('category');
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->string('thumbnail')->nullable();
        $table->text('description');
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
        Schema::dropIfExists('blogs');
    }
}
