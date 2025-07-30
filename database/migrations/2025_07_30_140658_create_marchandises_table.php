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
Schema::create('marchandises', function (Blueprint $table) {
    $table->id();
    $table->string('nom', 120);
    $table->string('reference')->nullable();
    $table->string('categorie')->nullable(); // Dangereux, Périssable, etc.
    $table->string('unite')->default('kg');
    $table->decimal('poids_moyen', 10, 2)->nullable();
    $table->decimal('tarif_par_defaut', 10, 2)->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marchandises');
    }
};
