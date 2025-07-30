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
      Schema::create('marchandises_transportees', function (Blueprint $table) {
    $table->id();
    $table->foreignId('trajet_id')->constrained('trajets')->onDelete('cascade');
    $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
    $table->foreignId('marchandise_id')->constrained('marchandises')->onDelete('cascade');
    $table->decimal('quantite', 10, 2);
    $table->decimal('poids_kg', 10, 2)->nullable();
    $table->decimal('volume_m3', 10, 2)->nullable();
    $table->decimal('valeur_estimee', 12, 2)->nullable();
    $table->string('lieu_livraison', 120)->nullable();

    $table->enum('statut', ['chargee', 'en_transit', 'livree', 'retour'])->default('chargee');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marchandises_transportees');
    }
};
