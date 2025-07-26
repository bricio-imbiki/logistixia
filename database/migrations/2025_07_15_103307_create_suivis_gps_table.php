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
        Schema::create('suivis_gps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camion_id')->constrained('camions');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('vitesse_kmh', 6, 2)->nullable();
            $table->decimal('niveau_carburant', 5, 2)->nullable();
            $table->dateTime('event_time')->default(now());
            $table->timestamps();
                $table->index(['camion_id', 'event_time']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivis_gps');
    }
};
