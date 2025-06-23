<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCaseStudiesAddCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_studies', function (Blueprint $table) {
            // Add category_id column
            $table->unsignedBigInteger('category_id')->nullable()->after('client_name');
            
            // Add foreign key constraint
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
                  
            // Keep the existing category column for backward compatibility
            // It will be used to store the category name as a denormalized field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_studies', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Drop the category_id column
            $table->dropColumn('category_id');
        });
    }
}
