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
        Schema::create('app_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('message', 300)->default('');
            $table->string('setting', 3)->default('APP');
            $table->string('can_delete', 1)->default('Y');
            $table->string('rowstatus', 3)->default('ACT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_statuses');
    }
};
