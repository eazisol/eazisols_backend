<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->text('tech_stack')->nullable()->after('description');
            $table->text('project_demand')->nullable()->after('tech_stack');
            $table->string('dedicated_team')->nullable()->after('project_demand');
            $table->string('client_location')->nullable()->after('dedicated_team');
            $table->text('solution_we_provide')->nullable()->after('client_location');
            $table->text('result')->nullable()->after('solution_we_provide');
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn([
                'tech_stack',
                'project_demand',
                'dedicated_team',
                'client_location',
                'solution_we_provide',
                'result',
            ]);
        });
    }
};
