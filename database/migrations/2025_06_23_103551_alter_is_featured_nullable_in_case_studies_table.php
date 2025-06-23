<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterIsFeaturedNullableInCaseStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->boolean('is_featured')->nullable()->default(false)->change();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->change();
        });
    }
}
