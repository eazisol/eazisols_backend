<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emp_finance_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->decimal('basic_salary', 12, 2)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();

            $table->enum('payment_type', ['bank_transfer', 'cash', 'cheque', 'crypto'])->default('bank_transfer');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emp_finance_information');
    }
};
