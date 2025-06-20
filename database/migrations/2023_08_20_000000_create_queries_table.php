<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('type', ['contact', 'cost_calculator'])->default('contact');
            $table->string('source')->nullable();
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('query_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->timestamps();
            
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
        });

        Schema::create('query_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('message');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
            
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('query_responses');
        Schema::dropIfExists('query_attachments');
        Schema::dropIfExists('queries');
    }
} 