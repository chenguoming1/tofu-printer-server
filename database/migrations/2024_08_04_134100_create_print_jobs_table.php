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
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('printer_id')->constrained();
            $table->foreignId('pricing_plan_id')->constrained();
            $table->string('job_type');
            $table->string('sub_category');
            $table->string('status');
            $table->integer('quantity');
            $table->float('amount');
            $table->string('currency_code');
            $table->string('payment_type');
            $table->string('payment_status');
            $table->json('selected_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
