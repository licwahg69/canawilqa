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
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('currency2_id');
            $table->decimal('conversion_value', 10, 2)->default('0.00');
            $table->string('rowstatus', 3)->default('ACT');
            $table->timestamps();
            $table->foreign("currency_id")->references("id")->on("currencies");
            $table->foreign("currency2_id")->references("id")->on("currencies");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
