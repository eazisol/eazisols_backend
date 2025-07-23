<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            // Check if 'type' column exists to rename it
            if (Schema::hasColumn('careers', 'type')) {
                $table->renameColumn('type', 'work_type');
            }
            // If 'type' doesn't exist but 'work_type' also doesn't exist, then add it
            elseif (!Schema::hasColumn('careers', 'work_type')) {
                $table->string('work_type')->nullable();
            }

            // Add other new columns
            if (!Schema::hasColumn('careers', 'workplace_type')) {
                $table->string('workplace_type')->nullable();
            }

            if (!Schema::hasColumn('careers', 'department')) {
                $table->string('department')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            // Revert 'work_type' back to 'type' only if 'work_type' exists
            if (Schema::hasColumn('careers', 'work_type')) {
                $table->renameColumn('work_type', 'type');
            }

            // Drop columns if they exist
            if (Schema::hasColumn('careers', 'workplace_type')) {
                $table->dropColumn('workplace_type');
            }

            if (Schema::hasColumn('careers', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};
