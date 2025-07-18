<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emp_job_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();

            $table->enum('work_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time');

            $table->date('joining_date')->nullable();
            $table->date('probation_end_date')->nullable();

            $table->unsignedBigInteger('reporting_manager_id')->nullable();
            $table->unsignedBigInteger('reporting_teamlead_id')->nullable();

            $table->string('work_location')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emp_job_information');
    }
};
