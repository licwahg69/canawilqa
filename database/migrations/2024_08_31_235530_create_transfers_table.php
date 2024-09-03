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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('canawilbank_id');
            $table->unsignedBigInteger('waytopay_id');
            $table->string('waytopay_reference', 25)->default('');
            $table->string('bank_image', 250)->default('');
            $table->string('rowstatus', 3)->default('ACT');
            $table->timestamps();
            $table->foreign("transaction_id")->references("id")->on("transactions");
            $table->foreign("canawilbank_id")->references("id")->on("canawil_banks");
            $table->foreign("waytopay_id")->references("id")->on("way_to_pays");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
