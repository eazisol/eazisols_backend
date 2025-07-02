<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQueriesTableForNewPayload extends Migration
{
    public function up()
    {
        Schema::table('queries', function (Blueprint $table) {
            // Rename existing columns (make sure original names exist)
            if (Schema::hasColumn('queries', 'name')) {
                $table->renameColumn('name', 'full_name');
            }
            if (Schema::hasColumn('queries', 'company_name')) {
                $table->renameColumn('company_name', 'company');
            }
            if (Schema::hasColumn('queries', 'message')) {
                $table->renameColumn('message', 'description');
            }

            // Add new columns (if not already added)
            if (!Schema::hasColumn('queries', 'services')) {
                $table->string('services')->nullable();
            }
            if (!Schema::hasColumn('queries', 'industry')) {
                $table->string('industry')->nullable();
            }
            if (!Schema::hasColumn('queries', 'other_industry')) {
                $table->string('other_industry')->nullable();
            }
            if (!Schema::hasColumn('queries', 'stage')) {
                $table->string('stage')->nullable();
            }
            if (!Schema::hasColumn('queries', 'timeline')) {
                $table->string('timeline')->nullable();
            }
            if (!Schema::hasColumn('queries', 'budget')) {
                $table->string('budget')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('queries', function (Blueprint $table) {
            // Revert column renames
            if (Schema::hasColumn('queries', 'full_name')) {
                $table->renameColumn('full_name', 'name');
            }
            if (Schema::hasColumn('queries', 'company')) {
                $table->renameColumn('company', 'company_name');
            }
            if (Schema::hasColumn('queries', 'description')) {
                $table->renameColumn('description', 'message');
            }

            // Drop added columns
            $columnsToDrop = [
                'services',
                'industry',
                'other_industry',
                'stage',
                'timeline',
                'budget',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('queries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}

