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
        Schema::create('transfer_buys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('buy_id');
            $table->decimal('amount', 10, 2)->default('0.00');
            $table->string('rowstatus', 3)->default('ACT');
            $table->timestamps();
            $table->foreign("transfer_id")->references("id")->on("transfers");
            $table->foreign("buy_id")->references("id")->on("buys");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_buys');
    }
};
