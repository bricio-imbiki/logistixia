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
     Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_utilisateur', 60)->unique();
            $table->string('email', 120)->unique();
            $table->string('mot_de_passe');
            $table->enum('role', ['admin', 'operateur', 'magasinier'])->default('operateur');
            $table->boolean('actif')->default(true);
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
