<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
        $table->renameColumn('type', 'work_type');

        $table->string('workplace_type')->nullable(); // Removed ->after('work_type')
        $table->string('department')->nullable();     // Removed ->after('workplace_type')
    });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
        $table->renameColumn('type', 'work_type');

        $table->string('workplace_type')->nullable(); // Removed ->after('work_type')
        $table->string('department')->nullable();     // Removed ->after('workplace_type')
    });
    }
};

