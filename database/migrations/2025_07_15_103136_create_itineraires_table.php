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
         Schema::create('itineraires', function (Blueprint $table) {
            $table->id();
            $table->string('lieu_depart', 120)->nullable();
            $table->string('lieu_arrivee', 120)->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('duree_estimee_h', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraires');
    }
};
