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
       // 4. Chauffeurs
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 60);
            $table->string('prenom', 60)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 120)->nullable();
            $table->text('adresse')->nullable();
            $table->string('permis_num', 50)->nullable();
            $table->string('permis_categorie', 10)->nullable();
            $table->date('permis_expire')->nullable();
            $table->enum('statut', ['titulaire', 'remplacant'])->default('titulaire');
            $table->date('date_embauche')->nullable();
            $table->smallInteger('experience_annees')->nullable();
            $table->string('cin_num', 30)->nullable();
            $table->boolean('apte_medicalement')->default(true);
            $table->string('photo_url', 255)->nullable();
            $table->string('document_permis', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chaufffeurs');
    }
};
