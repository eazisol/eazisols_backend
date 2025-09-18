<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            // Candidate details
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('qualification')->nullable();
            $table->year('year')->nullable();
            $table->integer('age')->nullable();
            $table->string('home_town')->nullable();
            $table->string('current_location')->nullable();

            // Interview details
            $table->date('date_of_interview');
            $table->time('interview_time')->nullable();
            $table->string('position_applied')->nullable();
            $table->enum('interview_type', ['onsite', 'online'])->default('onsite');
            $table->string('name_of_interviewer')->nullable();
            $table->text('remarks')->nullable();
            $table->text('technical_remarks')->nullable();
            $table->string('technical_interview_conducted_by')->nullable();

            // Job-related info
            $table->string('job_status')->nullable(); // e.g. offered, rejected, on-hold
            $table->string('marital_status')->nullable();
            $table->string('technical_skills')->nullable();
            $table->string('reference')->nullable();
            $table->string('last_company_name')->nullable();
            $table->integer('employee_count')->nullable();
            $table->decimal('total_experience', 5, 2)->nullable(); // e.g. 3.5 years
            $table->string('last_job_position')->nullable();
            $table->decimal('relevant_experience', 5, 2)->nullable();
            $table->decimal('last_current_salary', 10, 2)->nullable();
            $table->string('notice_period')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->boolean('negotiable')->default(false);
            $table->boolean('immediate_joining')->default(false);
            $table->text('reason_for_leaving')->nullable();
            $table->string('other_benefits')->nullable();

            // Miscellaneous
            $table->string('communication_skills')->nullable();
            $table->string('health_condition')->nullable();
            $table->boolean('currently_studying')->default(false);
            $table->boolean('interviewed_previously')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
