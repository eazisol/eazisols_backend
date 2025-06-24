<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTextColumnsToMediumtextInCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->mediumText('description')->change();
            $table->mediumText('responsibilities')->nullable()->change();
            $table->mediumText('requirements')->nullable()->change();
            $table->mediumText('benefits')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->text('description')->change();
            $table->text('responsibilities')->nullable()->change();
            $table->text('requirements')->nullable()->change();
            $table->text('benefits')->nullable()->change();
        });
    }
}
