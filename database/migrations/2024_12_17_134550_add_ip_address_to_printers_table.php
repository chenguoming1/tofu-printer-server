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
        Schema::table('printers', function (Blueprint $table) {
            $table->string('printer_ip')->nullable();
            $table->string('terminal_ip')->nullable();
            $table->string('terminal_port')->nullable()->default("8800");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn('printer_ip');
            $table->dropColumn('terminal_ip');
            $table->dropColumn('terminal_port');
        });
    }
};
