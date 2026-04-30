<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();

            $table->string('printer_name')->unique();
            $table->string('printer_description')->nullable();

            $table->timestamps();
        });

        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignUlid('printer_id')->constrained('printers', 'ulid');

            $table->string('receipt_data');
            $table->string('status')->default('pending');
            $table->string('error_message')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer');
        Schema::dropIfExists('print_jobs');
    }
};
