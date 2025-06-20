<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the existing unique constraint
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropUnique('blogs_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore the original constraint
        Schema::table('blogs', function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};
