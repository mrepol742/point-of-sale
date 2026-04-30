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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignUlid('cashier_ulid')->constrained('users', 'ulid');

            $table->json('products');
            $table->decimal('total', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->integer('total_items');
            $table->decimal('total_discount', 10, 2);
            $table->decimal('total_taxes', 10, 2);
            $table->decimal('total_payment', 10, 2);
            $table->decimal('total_change', 10, 2);
            $table->string('mode_of_payment');
            $table->string('reference_number')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sales_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('cashier_ulid')->constrained('users', 'ulid');

            $table->json('products');

            $table->timestamps();
        });

        Schema::create('end_of_days', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignUlid('cashier_ulid')->constrained('users', 'ulid');

            $table->decimal('cash_drawer', 10, 2);
            $table->decimal('total_sales', 10, 2);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sales_locks');
        Schema::dropIfExists('end_of_days');
    }
};
