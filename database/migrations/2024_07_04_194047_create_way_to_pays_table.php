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
        Schema::create('way_to_pays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('description', 50)->default('');
            $table->string('reference', 3)->default('');
            $table->string('rowstatus', 3)->default('ACT');
            $table->timestamps();
            $table->foreign("country_id")->references("id")->on("countries");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('way_to_pays');
    }
};
