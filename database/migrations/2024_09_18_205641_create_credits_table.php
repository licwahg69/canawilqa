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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('total_debt', 10, 2)->default('0.00');
            $table->string('creditstatus', 3)->default('PEN');
            $table->string('rowstatus', 3)->default('ACT');
            $table->timestamps();
            $table->foreign("transaction_id")->references("id")->on("transactions");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
