<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('location');
            $table->string('type'); 
            // $table->string('department')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('education')->nullable();
            $table->string('salary_range')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('status')->default('active'); 
            $table->boolean('featured')->default(false);
            $table->integer('vacancy_count')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
