<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCategoriesTableAddCaseStudyType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MySQL specific way to modify enum column
        DB::statement("ALTER TABLE categories MODIFY COLUMN type ENUM('blog', 'career', 'case_study') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to the original enum values
        DB::statement("ALTER TABLE categories MODIFY COLUMN type ENUM('blog', 'career') NOT NULL");
    }
}
