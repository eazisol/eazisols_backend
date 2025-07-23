<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('day_of_month'); // 1-31
            $table->time('time_of_day'); // e.g., 09:00:00
            $table->datetime('next_trigger_at');
            $table->boolean('notified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for efficient querying
            $table->index(['next_trigger_at', 'notified', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}