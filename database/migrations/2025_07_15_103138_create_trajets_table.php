
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camion_id')->nullable()->constrained('camions')->onDelete('set null');
            $table->foreignId('remorque_id')->nullable()->constrained('remorques')->onDelete('set null');
            $table->foreignId('chauffeur_id')->nullable()->constrained('chauffeurs')->onDelete('set null');
            $table->foreignId('itineraire_id')->nullable()->constrained('itineraires')->onDelete('set null');
            $table->dateTime('date_depart')->nullable();
            $table->dateTime('date_arrivee_etd')->nullable();
            $table->dateTime('date_arrivee_eta')->nullable();
            $table->enum('statut', ['prevu', 'en_cours', 'termine', 'annule'])->default('prevu');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trajets');
    }
};
