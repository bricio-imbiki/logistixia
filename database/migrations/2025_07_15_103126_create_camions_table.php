<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('camions', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('matricule', 20)->unique(); // Numéro d'immatriculation
            $table->string('marque', 50)->nullable();
            $table->string('modele', 50)->nullable();
            $table->smallInteger('annee')->nullable(); // Année de fabrication
            $table->integer('capacite_kg')->nullable(); // Capacité de chargement
            $table->enum('statut', ['disponible', 'en_mission', 'panne', 'maintenance'])->default('disponible');
            $table->boolean('est_interne')->default(true);
            $table->string('societe_proprietaire', 120)->nullable(); // Si loué
            $table->string('photo_url', 255)->nullable(); // Photo du camion

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camions');
    }
}
